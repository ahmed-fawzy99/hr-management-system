<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Services\ShiftServices;
use App\Services\ValidationServices;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShiftController extends Controller
{
    protected ShiftServices $shiftServices;
    protected ValidationServices $validationServices;
    public function __construct()
    {
        $this->shiftServices = new ShiftServices;
        $this->validationServices = new ValidationServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Shift/Shifts', [
            'shifts' => Shift::
            select(['id', 'name', 'start_time', 'end_time', 'shift_payment_multiplier', 'description'])
                ->withCount('employees')
                ->orderBy('id')
                ->paginate(config('constants.data.pagination_count')),
        ]);
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
    public function store(Request $request)
    {
        $shift = $this->validationServices->validateShiftCreationDetails($request);
        $this->shiftServices->createShift($request, $shift);

    }

    /**
     * Display the specified resource.
     */


    public function show(string $id, Request $request)
    {
        $shift = Shift::withCount("employees")->findOrFail($id);
        $employees = $shift->employees()
            ->where(function ($query) use ($request) {
                $query->where('employees.name', 'ILIKE', '%' . $request->term . '%')
                    ->orWhere('employees.email', 'ILIKE', '%' . $request->term . '%')
                    ->orWhere('employees.id', 'ILIKE', '%' . $request->term . '%')
                    ->orWhere('employees.phone', 'ILIKE', '%' . $request->term . '%')
                    ->orWhere('employees.national_id', 'ILIKE', '%' . $request->term . '%');
            })
            ->orderBy('employees.id')
            ->paginate(config('constants.data.pagination_count'), ['employees.id', 'employees.name', 'employees.phone', 'employees.email', 'employees.national_id']);

        return Inertia::render('Shift/ShiftView', [
            'shift' => $shift,
            'employees' => $employees,
            'searchPar' => $request->term,
        ]);
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
    public function update(Request $request, string $id)
    {
        return $this->shiftServices->updateShift($request, $id, $this->validationServices);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->shiftServices->deleteShift($id);
    }
}
