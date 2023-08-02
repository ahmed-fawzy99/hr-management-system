<?php

namespace App\Services;

use App\Models\Position;
use Inertia\Inertia;

class PositionServices
{
    public function renderIndexPage($request): \Inertia\Response
    {
        return Inertia::render('Position/Positions', [
            'positions' => Position::when($request->term, function ($query, $term) {
                $query->where('id', 'ILIKE', '%' . $term . '%')
                    ->orWhere('name', 'ILIKE', '%' . $term . '%')
                    ->orWhere('description', 'ILIKE', '%' . $term . '%');
            })
                ->select(['id', 'name', 'description'])
                ->withCount('employees')
                ->orderBy('id')->paginate(config('constants.data.pagination_count')),
        ]);
    }
    public function renderShowPage($id, $request): \Inertia\Response
    {
        $position = Position::withCount("employees")->findOrFail($id);
        $employees = $position->employees()->where(function ($query) use ($request) {
            $query->where('employees.normalized_name', 'ILIKE', '%' . normalizeArabic($request->term) . '%')
                ->orWhere('employees.email', 'ILIKE', '%' . $request->term . '%')
                ->orWhere('employees.id', 'ILIKE', '%' . $request->term . '%')
                ->orWhere('employees.phone', 'ILIKE', '%' . $request->term . '%')
                ->orWhere('employees.national_id', 'ILIKE', '%' . $request->term . '%');
        })
            ->orderBy('employees.id')
            ->paginate(config('constants.data.pagination_count'), ['employees.id', 'employees.name', 'employees.phone', 'employees.email', 'employees.national_id']);

        return Inertia::render('Position/PositionView', [
            'position' => $position,
            'employees' => $employees
        ]);
    }

}
