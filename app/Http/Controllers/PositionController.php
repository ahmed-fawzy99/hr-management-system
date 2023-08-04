<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Services\ValidationServices;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PositionController extends Controller
{
    protected ValidationServices $validationServices;
    public function __construct()
    {
        $this->validationServices = new ValidationServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Position/PositionCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $res = $this->validationServices->validatePositionCreationDetails($request);
        Position::create($res);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
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


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Position/PositionEdit', [
            'position' => Position::withCount('employees')->findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, ValidationServices $validationServices)
    {
        $res = $validationServices->validatePositionUpdateDetails($request, $id);
        Position::findOrFail($id)->update($res);
        return to_route('positions.show', ['position' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Position::findOrFail($id)->delete();
        return to_route('positions.index');
    }
}
