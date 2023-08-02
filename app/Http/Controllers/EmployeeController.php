<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Services\EmployeeServices;
use App\Services\ValidationServices;
use Illuminate\Http\Request;
use Inertia\Response;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, EmployeeServices $employeeServices)
    {
        return $employeeServices->renderIndexPage($request);
    }
    public function archivedIndex(Request $request, EmployeeServices $employeeServices)
    {
        return $employeeServices->renderArchivedIndexPage($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(EmployeeServices $employeeServices): Response
    {
        return $employeeServices->renderCreatePage();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ValidationServices $validationServices, EmployeeServices $employeeServices)
    {
        // Validate Input Firsts
        $res = $validationServices->validateEmployeeCreationDetails($request);

        // Employee Registration
        return $employeeServices->createEmployee($res);
    }

    // A function without parameters
    public function showMyProfile(EmployeeServices $employeeServices)
    {
        return !isAdmin() ? $employeeServices->renderShowPage(auth()->user()->id) : abort(403);
    }

    public function show(string $id, EmployeeServices $employeeServices): Response
    {
        return $employeeServices->renderShowPage($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, EmployeeServices $employeeServices)
    {
        return $employeeServices->renderEditPage($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, ValidationServices $validationServices, EmployeeServices $employeeServices)
    {
        // Validate Input Firsts
        $res = $validationServices->validateEmployeeUpdateDetails($request, $id);

        // Update Employee
        return $employeeServices->updateEmployee(Employee::findOrFail($id), $res);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, EmployeeServices $employeeServices)
    {
        return $employeeServices->deleteEmployee($id);
    }

    public function find(Request $request, EmployeeServices $employeeServices)
    {
        return $employeeServices->renderFindPage($request);
    }
}
