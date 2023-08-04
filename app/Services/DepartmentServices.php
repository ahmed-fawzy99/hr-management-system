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

}
