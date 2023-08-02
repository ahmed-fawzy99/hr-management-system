<?php

namespace App\Console;

use App\Models\Globals;
use App\Tasks\DailyAttendanceHandle;
use App\Tasks\MonthlyPayrollsHandle;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // This will use the timezone set at config/app.php
        $schedule->call(new DailyAttendanceHandle())->daily();    // Every Day at 00:00
        $schedule->command('activitylog:clean')->twiceMonthly(1, 16, '00:00'); // Clear Activity Log
        $schedule->call(new MonthlyPayrollsHandle())->monthlyOn(Globals::first()->payroll_day, '00:00');  // The first day of every month at 00:00

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
