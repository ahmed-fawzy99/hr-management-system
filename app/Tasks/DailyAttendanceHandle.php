<?php

namespace App\Tasks;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Globals;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Artisan;

class DailyAttendanceHandle
{
    public function __invoke(): void
    {
        logger("Daily Attendance Maintenance is running");
        Artisan::call('down --retry=1 --secret=HelloKittyImSoPretty --render="errors::503_daily"');

        // This will run at 12:00 AM every day, so we need to check yesterday's attendance
        $carbon = CarbonImmutable::now()->subDay();
        $date = $carbon->toDateString();

        // This condition is to check if yesterday was a weekend off day
        if (in_array(strtolower($carbon->dayName), json_decode(Globals::first()->weekend_off_days))) {
            logger("Yesterday was a weekend off day, nothing to do in the attendance scheduler");
            return;
        }

        // Mark all the employees who did not sign off as missed
        Attendance::where('date', $date)->whereNull('sign_off_time')->update([
            'status' => 'missed',
            'notes' => 'Employee did not sign off - Marked as Missed'
        ]);

        // If a user does not have attendance taken that day, create a record and mark it as missed
        $employees = Employee::all();
        foreach ($employees as $employee) {
            if (!$employee->attendances()->where('date', $date)->exists()) {
                $employee->attendances()->create([
                    'date' => $date,
                    'status' => 'missed',
                    'sign_in_time' => NULL,
                    'sign_off_time' => NULL,
                    'notes' => 'Automatically Marked as Missed',
                ]);
            }
        }

        Artisan::call('up');
        logger("DailyAttendanceHandle Completed");
    }
}
