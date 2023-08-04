<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Services\CalendarServices;
use App\Services\ValidationServices;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CalendarController extends Controller
{
    protected CalendarServices $calendarServices;
    protected ValidationServices $validationServices;
    public function __construct()
    {
        $this->calendarServices = new CalendarServices;
        $this->validationServices = new ValidationServices;
    }
    /**
     * Display a listing of the resource.
     */
    public function calendarIndex()
    {
        if (!isAdmin()) {
            // Don't expose leave requests to non-admins other than the employee's.
            $leaveRequests = \App\Models\Request::with('employee')->where('status', 1)->where('employee_id', '=', auth()->user()->id)->get();
        } else {
            $leaveRequests = \App\Models\Request::with('employee')->where('status', 1)->get();
        }
        return Inertia::render('Calendar/Calendar', [
            'calendarItems' => Calendar::get(),
            'leaveRequests' => $leaveRequests,
        ]);
    }

    public function index()
    {
        return Inertia::render('Calendar/CalendarItems', [
            'calendarItems' => Calendar::select(['id', 'title', 'type', 'start_date', 'end_date'])
                ->paginate(config('constants.data.pagination_count')),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Calendar/CalendarItemCreate', [
            'types' => ['holiday', 'meeting', 'event', 'other'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $res = $this->validationServices->validateCalendarItemCreationDetails($request);
        $this->calendarServices->createCalendarItem($res);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Inertia::render('Calendar/CalendarItemView', [
            'calendarItem' => Calendar::findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Calendar/CalendarItemEdit', [
            'types' => ['holiday', 'meeting', 'event', 'other'],
            'calendarItem' => Calendar::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $res = $this->validationServices->validateCalendarItemCreationDetails($request);
        return $this->calendarServices->updateCalendarItem($res, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Calendar::findOrFail($id)->delete();
        return to_route('calendars.index');
    }
}
