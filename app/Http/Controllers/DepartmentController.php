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

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DepartmentServices $departmentServices): Response
    {
        return $departmentServices->renderIndexPage($request);
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
    public function store(Request $request, ValidationServices $validationServices, DepartmentServices $departmentServices)
    {
        $res = $validationServices->validateDepartmentCreationDetails($request);
        return $departmentServices->createDepartment($res);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request, DepartmentServices $departmentServices): Response
    {
        return $departmentServices->renderShowPage($id, $request);
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
    public function update(Request $request, string $id, ValidationServices $validationServices, DepartmentServices $departmentServices)
    {
        $res = $validationServices->validateDepartmentUpdateDetails($request, $id);
        return $departmentServices->updateDepartment($res, $id);
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
