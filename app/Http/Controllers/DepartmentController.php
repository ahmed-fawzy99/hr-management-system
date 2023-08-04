<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Services\DepartmentServices;
use App\Services\ValidationServices;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DepartmentController extends Controller
{
    protected DepartmentServices $departmentServices;
    protected ValidationServices $validationServices;
    public function __construct()
    {
        $this->validationServices = new ValidationServices;
        $this->departmentServices = new DepartmentServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Department/Departments', [
            'departments' => Department::when($request->term, function ($query, $term) {
                $query->where('name', 'ILIKE', '%' . $term . '%');
            })
                ->select(['id', 'name'])
                ->withCount('employees')
                ->orderBy('id')
                ->paginate(config('constants.data.pagination_count')),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Department/DepartmentCreate', [
            'employees' => Employee::select(['id', 'name'])->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $res = $this->validationServices->validateDepartmentCreationDetails($request);
        return $this->departmentServices->createDepartment($res);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request): Response
    {
        $department = Department::withCount("employees")->findOrFail($id);
        $employees = $department->employees()
            ->where(function ($query) use ($request) {
                $query->where('employees.normalized_name', 'ILIKE', '%' . normalizeArabic($request->term) . '%')
                    ->orWhere('employees.email', 'ILIKE', '%' . $request->term . '%')
                    ->orWhere('employees.id', 'ILIKE', '%' . $request->term . '%')
                    ->orWhere('employees.phone', 'ILIKE', '%' . $request->term . '%')
                    ->orWhere('employees.national_id', 'ILIKE', '%' . $request->term . '%');
            })
            ->orderBy('employees.id')
            ->paginate(config('constants.data.pagination_count'),
                ['employees.id', 'employees.name', 'employees.phone', 'employees.email', 'employees.national_id']);

        return Inertia::render('Department/DepartmentView', [
            'department' => $department,
            'employees' => $employees,
            'manager' => $department->manager()->exists() ? $department->manager()->select(['employees.id', 'employees.name'])->first() : '',
            'searchPar' => $request->term,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Department/DepartmentEdit', [
            'department' => Department::with('manager')->withCount('employees')->findOrFail($id),
            'employees' => Employee::select(['id', 'name'])->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $res = $this->validationServices->validateDepartmentUpdateDetails($request, $id);
        return $this->departmentServices->updateDepartment($res, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Department::findOrFail($id)->delete();
        return to_route('departments.index');
    }
}
