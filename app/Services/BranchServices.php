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

    public function renderIndexPage($request){
        return Inertia::render('Branch/Branches', [
            'branches' => Branch::when($request->term, function ($query, $term) {
                $query->where('id', 'ILIKE', '%' . $term . '%')
                    ->orWhere('name', 'ILIKE', '%' . $term . '%')
                    ->orWhere('phone', 'ILIKE', '%' . $term . '%')
                    ->orWhere('email', 'ILIKE', '%' . $term . '%')
                    ->orWhere('address', 'ILIKE', '%' . $term . '%');
            })
                ->select(['id', 'name', 'phone', 'email', 'address'])
                ->withCount('employees')
                ->orderBy('id')
                ->paginate(config('constants.data.pagination_count')),
        ]);
    }
    public function renderShowPage($id, $request){
        $branch = Branch::withCount('employees')->findOrFail($id);
        $employees = $branch->employees()->where(function ($query) use ($request) {
            $query->where('employees.normalized_name', 'ILIKE', '%' . normalizeArabic($request->term) . '%')
                ->orWhere('employees.email', 'ILIKE', '%' . $request->term . '%')
                ->orWhere('employees.id', 'ILIKE', '%' . $request->term . '%')
                ->orWhere('employees.phone', 'ILIKE', '%' . $request->term . '%')
                ->orWhere('employees.national_id', 'ILIKE', '%' . $request->term . '%');
        })
            ->orderBy('employees.id')
            ->paginate(config('constants.data.pagination_count'), ['employees.id', 'employees.name', 'employees.phone', 'employees.email', 'employees.national_id']);

        return Inertia::render('Branch/BranchView', [
            'branch' => Branch::withCount('employees')->findOrFail($branch->id),
            'employees' => $employees,
            'manager' => $branch->manager()->exists() ? $branch->manager()->select(['employees.id', 'employees.name'])->first() : '',
        ]);
    }


}
