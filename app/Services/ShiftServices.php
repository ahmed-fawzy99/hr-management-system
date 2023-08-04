<?php

namespace App\Services;

use App\Models\Shift;
use Carbon\Carbon;
use Inertia\Inertia;

class ShiftServices
{
    public function createShift($request, $shift): \Illuminate\Http\RedirectResponse
    {
        $startTime = Carbon::createFromTime($request->start_time['hours'], $request->start_time['minutes'], 0)->format('H:i:s');
        $endTime = Carbon::createFromTime($request->end_time['hours'], $request->end_time['minutes'], 0)->format('H:i:s');
        Shift::firstOrCreate([
            'name' => $shift['name'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'shift_payment_multiplier' => $shift['shift_payment_multiplier'],
            'description' => $shift['description'],
        ]);
        return to_route('shifts.index');
    }
    public function updateShift($request, $id, $validationServices): \Illuminate\Http\RedirectResponse
    {
        if (is_array($request->start_time) && is_array($request->end_time)) {
            $res = $validationServices->validateShiftUpdateDetails($request, $id);
            $startTime = Carbon::createFromTime($request->start_time['hours'], $request->start_time['minutes'], 0)->format('H:i:s');
            $endTime = Carbon::createFromTime($request->end_time['hours'], $request->end_time['minutes'], 0)->format('H:i:s');
            $res['start_time'] = $startTime;
            $res['end_time'] = $endTime;
        } else {
            $res = $validationServices->validateShiftUpdateDetailsNotArray($request, $id);
        }
        $shift = Shift::findOrFail($id);
        $shift->update($res);
        return to_route('shifts.show', ['shift' => $shift->id]);
    }

    /**
     * An employee cannot have no assigned shifts.
     * If we are deleting a shift, we must reassign the employees to another shift.
     */
    public function deleteShift($id): \Illuminate\Http\RedirectResponse
    {
        if (Shift::count() === 1) {
            return back()->withErrors(['only_shift' => 'This is the only shift in the system. You cannot delete it.']);
        }

        $shift = Shift::findOrFail($id);

        if ($shift->employees()->count() > 0) {
            foreach ($shift->employees()->get() as $employee) {
                $curShift = $employee->employeeShifts()->whereNull('end_date')->first();
                $curShift->update(['shift_id' => Shift::where('id', '!=', $id)->first()->id,]);
            }
        }
        $shift->delete();
        return to_route('shifts.index');
    }


}
