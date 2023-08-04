<?php

namespace App\Services;

use App\Models\Branch;
use Arr;
use Inertia\Inertia;

class BranchServices
{
    public function createBranch($res): \Illuminate\Http\RedirectResponse
    {
        $manager_id = $res['manager_id'] ?? null;
        $br = Arr::except($res, ['manager_id']);

        $branch = Branch::create($br);

        if ($manager_id) {
            $commonServices = new CommonServices();
            $commonServices->setManager($manager_id, $branch->id, 0);
        }
        return redirect()->back();
    }
    public function updateBranch($res, $id): \Illuminate\Http\RedirectResponse
    {
        $manager_id = $res['manager_id'] ?? null;
        $branch = Branch::findOrFail($id);

        $branch->update(Arr::except($res, ['manager_id']));

        $commonServices = new CommonServices();
        if ($manager_id) {
            $commonServices->updateManager($manager_id, $branch->id, 0);
        } else {
            $commonServices->removeManager($branch->id, 0);
        }
        return to_route('branches.show', ['branch' => $branch->id]);
    }
}
