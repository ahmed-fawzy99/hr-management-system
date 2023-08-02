<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Employee;
use App\Services\BranchServices;
use App\Services\ValidationServices;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, BranchServices $branchServices)
    {
        return $branchServices->renderIndexPage($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Branch/BranchCreate', [
            'employees' => Employee::select(['id', 'name'])->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ValidationServices $validationServices, BranchServices $branchServices)
    {
        $res = $validationServices->validateBranchCreationDetails($request);
        return $branchServices->createBranch($res);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request, BranchServices $branchServices)
    {
        return $branchServices->renderShowPage($id, $request);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Branch/BranchEdit', [
            'branch' => Branch::with('manager')->withCount('employees')->findOrFail($id),
            'employees' => Employee::select(['id', 'name'])->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, ValidationServices $validationServices, BranchServices $branchServices)
    {
        $res = $validationServices->validateBranchUpdateDetails($request, $id);
        return $branchServices->updateBranch($res, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Branch::findOrFail($id)->delete();
        return to_route('branches.index');
    }
}
