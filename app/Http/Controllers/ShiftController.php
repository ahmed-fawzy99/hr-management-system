<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Services\ShiftServices;
use App\Services\ValidationServices;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShiftController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(ShiftServices $shiftServices)
    {
        return $shiftServices->renderIndexPage();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Shift/ShiftCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ShiftServices $shiftServices, ValidationServices $validationServices)
    {
        $shift = $validationServices->validateShiftCreationDetails($request);
        $shiftServices->createShift($request, $shift);

    }

    /**
     * Display the specified resource.
     */


    public function show(string $id, Request $request, ShiftServices $shiftServices)
    {
        return $shiftServices->renderShowPage($id, $request);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $shift = Shift::withCount('employees')->findOrFail($id);
        return Inertia::render('Shift/ShiftEdit', [
            'shift' => $shift,
            'name' => $shift->getRawOriginal('name'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, ShiftServices $shiftServices, ValidationServices $validationServices)
    {
        return $shiftServices->updateShift($request, $id, $validationServices);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, ShiftServices $shiftServices)
    {
        return $shiftServices->deleteShift($id);
    }
}
