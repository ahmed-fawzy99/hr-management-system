<?php

namespace App\Services;

use App\Models\ArchivedEmployee;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeePosition;
use App\Models\EmployeeSalary;
use App\Models\EmployeeShift;
use App\Models\Position;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class EmployeeServices
{
    public function createEmployee($res): \Illuminate\Http\RedirectResponse
    {
        if (is_null($res['hired_on'])) {
            $res['hired_on'] = now()->format('Y-m-d');
        }
        $res['password'] = bcrypt('SoftKittyWarmKittyLittleBallOfFurr');
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

    public function renderFindPage($request): \Inertia\Response
    {
        return Inertia::render('Employee/EmployeeFind', [
            'employees' => Employee::when($request->term, function ($query, $term) {
                $query
                    ->where('name', 'ILIKE', '%' . $term . '%')
                    ->orWhere('id', 'ILIKE', '%' . $term . '%')
                    ->orWhere('email', 'ILIKE', '%' . $term . '%')
                    ->orWhere('phone', 'ILIKE', '%' . $term . '%')
                    ->orWhere('national_id', 'ILIKE', '%' . $term . '%');
            })->get(),
        ]);
    }
    public function renderEditPage($id): \Inertia\Response
    {
        return Inertia::render('Employee/EmployeeEdit', [
            'employee' => Employee::with("salaries", "roles", 'employeeShifts.shift', 'employeePositions.position')
                ->leftjoin('departments', 'employees.department_id',
                    '=', 'departments.id')
                ->leftJoin('branches', 'employees.branch_id', '=', 'branches.id')
                ->where('employees.id', $id)
                ->select('employees.id', 'employees.name', 'employees.phone', 'employees.national_id', 'employees.email',
                    'employees.address', 'employees.bank_acc_no', 'employees.hired_on', 'departments.name as department_name',
                    'departments.id as department_id', 'branches.id as branch_id', 'branches.name as branch_name', 'employees.is_remote')
                ->first(),
            'departments' => Department::select(['id', 'name'])->get(),
            'branches' => Branch::select(['id', 'name'])->get(),
            'positions' => Position::select(['id', 'name'])->get(),
            'roles' => DB::select('SELECT name FROM roles'),
            'shifts' => Shift::get(),
        ]);
    }
    public function renderShowPage($id): \Inertia\Response
    {
        return Inertia::render('Employee/EmployeeView', [
            'employee' => Employee::with("salaries", "roles", 'employeeShifts.shift', 'employeePositions.position', 'manages')
                ->leftjoin('departments', 'employees.department_id',
                    '=', 'departments.id')
                ->leftJoin('branches', 'employees.branch_id', '=', 'branches.id')
                ->where('employees.id', $id)
                ->select('employees.id', 'employees.name', 'employees.phone', 'employees.national_id', 'employees.email',
                    'employees.address', 'employees.bank_acc_no', 'departments.name as department_name',
                    'departments.id as department_id', 'branches.id as branch_id', 'branches.name as branch_name',
                    'employees.hired_on', 'employees.is_remote')
                ->first(),
        ]);
    }

    public function renderCreatePage(): \Inertia\Response
    {
        return Inertia::render('Employee/EmployeeCreate', [
            'departments' => Department::select(['id', 'name'])->get(),
            'branches' => Branch::select(['id', 'name'])->get(),
            'positions' => Position::select(['id', 'name'])->get(),
            'roles' => DB::select('SELECT name FROM roles'),
            'shifts' => Shift::get(),
        ]);
    }

    public function renderIndexPage($request): \Inertia\Response
    {
        $sortDir = 'asc';
        if ($request->has('sort')) {
            $request->validate([
                'sort' => 'in:id,name',
                'sort_dir' => 'required|boolean',
            ]);
            $sortDir = $request->sort_dir ? 'asc' : 'desc';
        }


        return Inertia::render('Employee/Employees', [
            'employees' => Employee::when($request->term, function ($query, $term) {
                $query->where('normalized_name', 'ILIKE', '%' . normalizeArabic($term) . '%')
                    ->orWhere('email', 'ILIKE', '%' . $term . '%')
                    ->orWhere('id', 'ILIKE', '%' . $term . '%')
                    ->orWhere('phone', 'ILIKE', '%' . $term . '%')
                    ->orWhere('national_id', 'ILIKE', '%' . $term . '%');
            })->orderBy($request->sort ?? 'id', $sortDir)->select(['id', 'name', 'email', 'phone', 'national_id'])
                ->paginate(config('constants.data.pagination_count')),
        ]);
    }

    public function renderArchivedIndexPage($request): \Inertia\Response
    {
        $sortDir = 'asc';
        if ($request->has('sort')) {
            $request->validate([
                'sort' => 'in:id,name',
                'sort_dir' => 'required|boolean',
            ]);
            $sortDir = $request->sort_dir ? 'asc' : 'desc';
        }

        return Inertia::render('Employee/ArchievedEmployees', [
            'employees' => ArchivedEmployee::when($request->term, function ($query, $term) {
                $query->where('name', 'ILIKE', '%' . $term . '%')
                    ->orWhere('email', 'ILIKE', '%' . $term . '%')
                    ->orWhere('id', 'ILIKE', '%' . $term . '%')
                    ->orWhere('phone', 'ILIKE', '%' . $term . '%')
                    ->orWhere('national_id', 'ILIKE', '%' . $term . '%');
            })->orderBy($request->sort ?? 'released_on', $sortDir)
                ->select(['id', 'name', 'email', 'phone', 'national_id', 'hired_on', 'released_on'])
                ->paginate(config('constants.data.pagination_count')),
        ]);
    }

}
