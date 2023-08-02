<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Department;
use Arr;
use Inertia\Inertia;

class DepartmentServices
{
    public function createDepartment($res): \Illuminate\Http\RedirectResponse
    {
        $manager_id = $res['manager_id'] ?? null;
        $dp = \Illuminate\Support\Arr::except($res, ['manager_id']);

        $department = Department::create($dp);
        if ($manager_id){
            $commonServices = new CommonServices();
            $commonServices->setManager($manager_id, $department->id, 1);
        }
        return redirect()->back();
    }
    public function updateDepartment($res, $id): \Illuminate\Http\RedirectResponse
    {
        $manager_id = $res['manager_id'] ?? null;
        $department = Department::findOrFail($id);

        $department->update(\Illuminate\Support\Arr::except($res, ['manager_id']));

        $commonServices = new CommonServices();
        if ($manager_id) {
            $commonServices->updateManager($manager_id, $department->id, 1);
        } else {
            $commonServices->removeManager($department->id, 1);
        }

        return to_route('departments.show', ['department' => $department->id]);
    }

    public function renderIndexPage($request): \Inertia\Response
    {
        return Inertia::render('Department/Departments', [
            'departments' => Department::when($request->term, function ($query, $term) {
                $query->where('name', 'ILIKE', '%' . $term . '%');
            })
                ->select(['id', 'name'])
                ->withCount('employees')
                ->orderBy('id')
                ->paginate(config('constants.data.pagination_count')),
        ]);
    }
    public function renderShowPage($id, $request): \Inertia\Response
    {
        $department = Department::withCount("employees")->findOrFail($id);
        $employees = $department->employees()
            ->where(function ($query) use ($request) {
                $query->where('employees.normalized_name', 'ILIKE', '%' . normalizeArabic($request->term) . '%')
                    ->orWhere('employees.email', 'ILIKE', '%' . $request->term . '%')
                    ->orWhere('employees.id', 'ILIKE', '%' . $request->term . '%')
                    ->orWhere('employees.phone', 'ILIKE', '%' . $request->term . '%')
                    ->orWhere('employees.national_id', 'ILIKE', '%' . $request->term . '%');
            })
            ->orderBy('employees.id')
            ->paginate(config('constants.data.pagination_count'),
                ['employees.id', 'employees.name', 'employees.phone', 'employees.email', 'employees.national_id']);

        return Inertia::render('Department/DepartmentView', [
            'department' => $department,
            'employees' => $employees,
            'manager' => $department->manager()->exists() ? $department->manager()->select(['employees.id', 'employees.name'])->first() : '',
            'searchPar' => $request->term,
        ]);
    }

}
