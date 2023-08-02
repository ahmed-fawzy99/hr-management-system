<?php

namespace App\Tasks;

use App\Models\Addition;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class MonthlyPayrollsHandle
{
    public function __invoke(): void
    {
        logger("Monthly Maintenance is running");
        Artisan::call('down --retry=1 --secret=HelloKittyImNotSoPretty --render="errors::503_monthly"');

        // Generate Payrolls
        $date = Carbon::now()->toDateString();
        $employeesCount = Employee::count();

        for ($i = 1; $i <= $employeesCount; $i++) {
            $employee = Employee::find($i);

            $payroll = Payroll::create([
                'employee_id' => $employee->id,
                'currency' => $employee->salary()[0],
                'base' => $employee->salary()[1],
                'total_payable' => $employee->salary()[1],
                'performance_multiplier' => 1,
                "due_date" => $date,
            ]);
            Addition::create([
                'payroll_id' => $payroll->id,
                "due_date" => $date,
            ]);
            Deduction::create([
                'payroll_id' => $payroll->id,
                "due_date" => $date,
            ]);
        }

        Artisan::call('up');
        logger("MonthlyMaintenance Completed");
    }
}
