<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Addition;
use App\Models\Branch;
use App\Models\Calendar;
use App\Models\Deduction;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeEvaluation;
use App\Models\EmployeePosition;
use App\Models\EmployeeSalary;
use App\Models\EmployeeShift;
use App\Models\Globals;
use App\Models\Manager;
use App\Models\Metric;
use App\Models\Payroll;
use App\Models\Position;
use App\Models\Request;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Globals
        $this->seedGlobals();

        // Branches & Departments & Shifts
        $this->seedBranchesDepartmentsPositionsShifts();

        // Create Test Employees
        $root = Employee::factory()->create([
            'name' => 'Super Root',
            'email' => 'super@root.com',
            'phone' => '01001095076',
            'national_id' => '29904268801154',
            'hired_on' => '2023-01-25',
            'password' => '$2y$10$7BMn8WlpLkUB64fCCCVCvuFbqp4dO34dLL/a7MjMdoITz0FOIOZ.G',
        ]);

        Employee::factory(14)->create();
        Metric::factory(5)->create();

        // Create roles
        $roles = [
            'admin',
            'employee',
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        $knighthood = Role::findByName('admin');
        $root->assignRole($knighthood);

        // Random Assignments
        foreach (Employee::all() as $index => $employee) {
            if ($employee->id != 1) {
                $employee->assignRole(Role::findByName('employee'));
            }
            EmployeePosition::create([
                'employee_id' => $employee->id,
                'position_id' => $index % 4 + 1,
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
            ]);
            EmployeeShift::create([
                'employee_id' => $employee->id,
                'shift_id' => $index % 2 + 1,
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
            ]);

            $currencies = ['USD', 'EGP', 'SAR', 'EUR', 'GBP'];
            EmployeeSalary::create([
                'employee_id' => $employee->id,
                'currency' => $currencies[array_rand($currencies)],
                'salary' => fake()->numberBetween(2000, 8000),
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
            ]);
        }

        // Assign Managers to branch #1 and department #1
        Manager::create([
            'employee_id' => 1,
            'branch_id' => 1,
            'department_id' => null,
        ]);
        Manager::create([
            'employee_id' => 2,
            'branch_id' => null,
            'department_id' => 1,
        ]);

        // Generate Random Payrolls
        $this->generateRandomPayrolls();

        // Generate Random Requests & Calendar Items
        $this->seedRequests();
        $this->seedCalendarItems();
    }

    private function seedGlobals(): void
    {
        Globals::create([
            'organization_name' => 'Global Solutions Inc.',
            'organization_address' => '123 Main Street, Anytown, USA',
            'absence_limit' => 30,
            'email' => 'info@globalsolutions.com',
        ]);
    }
    private function seedBranchesDepartmentsPositionsShifts(): void
    {
        Branch::factory()->create([
            'name' => 'Cairo Branch',
        ]);

        Branch::factory()->create([
            'name' => 'NY Branch',
        ]);

        Department::create([
            'name' => 'IT',
        ]);

        Department::create([
            'name' => 'HR',
        ]);

        Department::create([
            'name' => 'Sales',
        ]);

        Department::create([
            'name' => 'Customer Service',
        ]);

        Position::create([
            'name' => 'CEO',
            'description' => 'Chief Executive Officer',
        ]);

        Position::create([
            'name' => 'Marketing Manager',
            'description' => 'Responsible for all marketing activities',
        ]);

        Position::create([
            'name' => 'Graphic Designer',
            'description' => 'Responsible for all graphic design activities',
        ]);

        Position::create([
            'name' => 'Developer',
            'description' => 'Responsible for all development activities',
        ]);

        Shift::create([
            'name' => "Day Shift",
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
        ]);

        Shift::create([
            'name' => "Night Shift",
            'start_time' => '16:00:00',
            'end_time' => '00:00:00',
        ]);
    }

    private function seedRequests(): void
    {
        for ($i = 1; $i <= Employee::count(); $i++) {
            $requestTypes = ['complaint', 'payment', 'leave', 'other'];
            Request::create([
                'employee_id' => $i,
                'type' => $requestTypes[array_rand($requestTypes)],
                'start_date' => Carbon::now()->addDays(fake()->numberBetween(1, 100))->toDateString(),
                'end_date' => null,
                'message' => fake()->sentence(10),
                'status' => fake()->numberBetween(0, 2),
                'admin_response' => fake()->sentence(10),
                'is_seen' => fake()->boolean,
            ]);
        }
    }

    private function seedCalendarItems(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $calendarTypes = ['holiday', 'meeting', 'event', 'other'];
            $start_date = Carbon::now()->addDays(fake()->numberBetween(1, 30));
            Calendar::create([
                'start_date' => $start_date->toDateString(),
                'end_date' => $start_date->addDays(fake()->numberBetween(0, 2))->toDateString(),
                'title' => fake()->sentence(2),
                'type' => $calendarTypes[array_rand($calendarTypes)],
            ]);
        }
    }
    private function generateRandomPayrolls(){
        for ($i = 1; $i <= Employee::count(); $i++) {
            $employee_id = Employee::find($i)->id;

            $p = Payroll::factory()->create([
                'employee_id' => $employee_id,
                "due_date" => Carbon::now()->toDateString(),
            ]);

            $metrics = Metric::get();
            for ($j = 0; $j < $metrics->count(); $j++) {
                $metric_id = $metrics[$j]->id;
                EmployeeEvaluation::create([
                    'employee_id' => $employee_id,
                    'metric_id' => $metric_id,
                    'payroll_id' => $p->id,
                    'date' => Carbon::now()->toDateString(),
                ]);
            }

            Addition::factory()->create([
                'payroll_id' => $p->id,
                "due_date" => Carbon::now()->toDateString(),
            ]);

            Deduction::factory()->create([
                'payroll_id' => $p->id,
                "due_date" => Carbon::now()->toDateString(),
            ]);
        }
    }

}
