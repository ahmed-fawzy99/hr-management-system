<?php

namespace App\Services;

use App\Mail\EmployeeRegisterationCredentials;
use App\Models\ArchivedEmployee;
use App\Models\Employee;
use App\Models\EmployeePosition;
use App\Models\EmployeeSalary;
use App\Models\EmployeeShift;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class EmployeeServices
{
    public function createEmployee($res): \Illuminate\Http\RedirectResponse
    {
        if (is_null($res['hired_on'])) {
            $res['hired_on'] = now()->format('Y-m-d');
        }
        $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
        $res['password'] = bcrypt($password);
        $emp = Employee::create([
            'name' => $res['name'],
            'email' => $res['email'],
            'phone' => $res['phone'],
            'national_id' => $res['national_id'],
            'address' => $res['address'],
            'bank_acc_no' => $res['bank_acc_no'],
            'hired_on' => $res['hired_on'],
            'branch_id' => $res['branch_id'],
            'department_id' => $res['department_id'],
            'is_remote' => $res['is_remote'],
            'password' => $res['password'],
        ]);

        // Salary Registration
        EmployeeSalary::create([
            'employee_id' => $emp['id'],
            'currency' => $res['currency'],
            'salary' => $res['salary'],
            'start_date' => Carbon::createFromFormat('Y-m-d', $res['hired_on'])->addMonth()->startOfMonth(),
            'end_date' => null,
        ]);

        // Assign Position
        if (isset($res['position_id'])) {
            EmployeePosition::create([
                'employee_id' => $emp['id'],
                'position_id' => $res['position_id'],
                'start_date' => Carbon::createFromFormat('Y-m-d', $res['hired_on']),
                'end_date' => null,
            ]);
        }

        // Assign Shift
        if (isset($res['shift_id'])) {
            EmployeeShift::create([
                'employee_id' => $emp['id'],
                'shift_id' => $res['shift_id'],
                'start_date' => Carbon::createFromFormat('Y-m-d', $res['hired_on']),
                'end_date' => null,
            ]);
        }

        // Assign Role
        $emp->assignRole($res['role']);

        // Send Email to user with credentials
        Mail::to($emp->email)->send(new EmployeeRegisterationCredentials([
            'name' => $emp->name,
            'email' => $emp->email,
            'password' => $password,
        ]));

        return to_route('employees.show', ['employee' => $emp->id]);
    }

    public function updateEmployee($employee, $res): \Illuminate\Http\RedirectResponse
    {

        // Update Personal Details
        $employee->update([
            'name' => $res['name'],
            'email' => $res['email'],
            'phone' => $res['phone'],
            'national_id' => $res['national_id'],
            'address' => $res['address'],
            'bank_acc_no' => $res['bank_acc_no'],
            'hired_on' => $res['hired_on'],
            'branch_id' => $res['branch_id'],
            'department_id' => $res['department_id'],
            'is_remote' => $res['is_remote'],
        ]);

        // Update Shifts, Salary, Position, and Permissions
        $curPos = $employee->employeePositions()->whereNull('end_date')->first();
        if ($curPos->position_id != $res['position_id']) {
            $curPos->update([
                'end_date' => Carbon::now()->format('Y-m-d'),
            ]);
            $employee->employeePositions()->create([
                'employee_id' => $employee->id,
                'position_id' => $res['position_id'],
                'start_date' => Carbon::now()->format('Y-m-d'),
                'end_date' => null,
            ]);
        }

        $curShift = $employee->employeeShifts()->whereNull('end_date')->first();
        if ($curShift->shift_id != $res['shift_id']) {
            $curShift->update([
                'end_date' => Carbon::now()->format('Y-m-d'),
            ]);
            $employee->employeeShifts()->create([
                'employee_id' => $employee->id,
                'shift_id' => $res['shift_id'],
                'start_date' => Carbon::now()->format('Y-m-d'),
                'end_date' => null,
            ]);
        }

        $curSalary = $employee->salaries()->whereNull('end_date')->first();
        if ($curSalary->salary != $res['salary'] || $curSalary->currency != $res['currency']) {
            $employee->salaries()->whereNull('end_date')->first()->update([
                'end_date' => Carbon::now()->format('Y-m-d'),
            ]);
            $employee->salaries()->create([
                'employee_id' => $employee->id,
                'currency' => $res['currency'],
                'salary' => $res['salary'],
                'start_date' => Carbon::now()->format('Y-m-d'),
                'end_date' => null,
            ]);
        }

        $currentRole = $employee->getRoleNames()->first();
        if ($currentRole != $res['role']) {
            $employee->removeRole($currentRole);
            $employee->assignRole($res['role']);
            $employee->save();
        }
        return to_route('employees.show', ['employee' => $employee->id]);
    }

    public function deleteEmployee($id): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {

        $employee = Employee::findOrFail($id);

        if ($employee->id == auth()->user()->id) {
            return response()->json(['Error' => 'You cannot delete yourself.'], 403);
        }

        // Move employee to archived_employees first..
        ArchivedEmployee::create([
            'name' => $employee->name,
            'phone' => $employee->phone,
            'email' => $employee->email,
            'national_id' => $employee->national_id,
            'address' => $employee->address,
            'bank_acc_no' => $employee->bank_acc_no,
            'hired_on' => $employee->hired_on,
            'released_on' => Carbon::now()->format('Y-m-d'),
            'was_remote' => $employee->is_remote,
        ]);

        // Delete employee
        $employee->delete();

        return to_route('employees.index');
    }
}
