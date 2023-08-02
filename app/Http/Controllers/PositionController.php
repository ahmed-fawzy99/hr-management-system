<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Services\PositionServices;
use App\Services\ValidationServices;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PositionServices $positionServices)
    {
        return $positionServices->renderIndexPage($request);
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
    public function store(Request $request, ValidationServices $validationServices)
    {
        $res = $validationServices->validatePositionCreationDetails($request);
        Position::create($res);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request, PositionServices $positionServices)
    {
        return $positionServices->renderShowPage($id, $request);
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
