<?php

namespace App\Http\Controllers;

use App\Models\ArchivedEmployee;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Shift;
use App\Services\EmployeeServices;
use App\Services\ValidationServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class EmployeeController extends Controller
{
    protected EmployeeServices $employeeServices;
    protected ValidationServices $validationServices;
    public function __construct()
    {
        $this->employeeServices = new EmployeeServices;
        $this->validationServices = new ValidationServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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

    public function archivedIndex(Request $request)
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

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Employee/EmployeeCreate', [
            'departments' => Department::select(['id', 'name'])->get(),
            'branches' => Branch::select(['id', 'name'])->get(),
            'positions' => Position::select(['id', 'name'])->get(),
            'roles' => DB::select('SELECT name FROM roles'),
            'shifts' => Shift::get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate Input Firsts
        $res = $this->validationServices->validateEmployeeCreationDetails($request);
        // Employee Registration
        return $this->employeeServices->createEmployee($res);
    }

    // A function without parameters
    public function showMyProfile()
    {
        return $this->show(auth()->user()->id);
    }

    public function show(string $id): Response
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate Input Firsts
        $res = $this->validationServices->validateEmployeeUpdateDetails($request, $id);

        // Update Employee
        return $this->employeeServices->updateEmployee(Employee::findOrFail($id), $res);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->employeeServices->deleteEmployee($id);
    }

    public function find(Request $request)
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
}
