<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\Globals;
use App\Services\AttendanceServices;
use App\Services\CommonServices;
use App\Services\ValidationServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function attendanceDashboard()
    {
        return Inertia::render('Attendance/AttendanceDashboard', [
            "EmployeeStats" => auth()->user()->myStats(),
        ]);
    }

    public function index(Request $request, AttendanceServices $attendanceServices)
    {
        return $attendanceServices->renderIndexPage($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, AttendanceServices $attendanceServices)
    {
        return $attendanceServices->renderCreatePage($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,
                          ValidationServices $validationServices,
                          AttendanceServices $attendanceServices,
                          CommonServices $commonServices)
    {
        $res = $validationServices->validateMassAttendanceCreation($request);

        return $attendanceServices->createAttendance($res, $commonServices);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id){}

    public function dayShow(string $day, AttendanceServices $attendanceServices)
    {
        $validator = Validator::make(['dateParameter' => $day], [
            'dateParameter' => 'required|string|date_format:Y-m-d',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([$errors]);
        }

        return $attendanceServices->renderDayAttendancePage($day);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id){}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id){}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){}

    public function dayDelete(Request $request, AttendanceServices $attendanceServices)
    {
        $res = $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);
        return $attendanceServices->deleteDayAttendance($res);
    }

    /***
     **************** SELF-TAKING ATTENDANCE SECTION ****************
     ***/
    public function dashboardSignInAttendance(Request $request, AttendanceServices $attendanceServices)
    {
        return $attendanceServices->selfSignInAttendance($request);
    }

    public function dashboardSignOffAttendance(Request $request, AttendanceServices $attendanceServices)
    {
        return $attendanceServices->selfSignOffAttendance($request);
    }


}
