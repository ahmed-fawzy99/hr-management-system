<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeePosition;
use App\Models\EmployeeSalary;
use App\Models\EmployeeShift;
use App\Models\Globals;
use App\Models\Manager;
use App\Models\Position;
use App\Models\Shift;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class StarterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Globals::create([
            'organization_name' => 'Global Solutions Inc.',
            'organization_address' => '123 Main Street, Anytown, USA',
            'absence_limit' => 30,
            'email' => 'info@globalsolutions.com',
        ]);

        $branch = Branch::factory()->create([
            'name' => 'Main Branch',
        ]);

        $department = Department::create([
            'name' => 'HR',
        ]);

        Position::create([
            'name' => 'HR Manager',
            'description' => 'Responsible for all HR activities',
        ]);
        Shift::create([
            'name' => "Day Shift",
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
        ]);

        $root = Employee::factory()->create([
            'name' => 'Super Root',
            'email' => 'super@root.com',
            'phone' => '01001095076',
            'national_id' => '29904268801154',
            'hired_on' => '2023-01-25',
            'password' => '$2y$10$7BMn8WlpLkUB64fCCCVCvuFbqp4dO34dLL/a7MjMdoITz0FOIOZ.G',
            'branch_id' => 1,
            'department_id' => 1,
        ]);

        EmployeePosition::create([
            'employee_id' => 1,
            'position_id' => 1,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => null,
        ]);

        EmployeeShift::create([
            'employee_id' => 1,
            'shift_id' => 1,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => null,
        ]);

        EmployeeSalary::create([
            'employee_id' => 1,
            'currency' => 'USD',
            'salary' => 10000,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => null,
        ]);

        Manager::create([
            'employee_id' => 1,
            'branch_id' => 1,
            'department_id' => null,
        ]);
        Manager::create([
            'employee_id' => 1,
            'branch_id' => null,
            'department_id' => 1,
        ]);

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
    }
}
