<?php

namespace App\Http\Controllers;

use App\Models\Metric;
use App\Services\ValidationServices;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MetricsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Metric/Metrics', [
            'metrics' => Metric::select('id', 'criteria', 'weight', 'step')->paginate(config('constants.data.pagination_count')),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Metric/MetricCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ValidationServices $validationServices)
    {
        $res = $validationServices->validateMetricCreationDetails($request);
        $res['step'] /= 100;
        $metric = Metric::create($res);
        return to_route('metrics.show', ['metric' => $metric->id, 'id' => $metric->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Inertia::render('Metric/MetricView', [
            'metric' => Metric::findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Metric/MetricEdit', [
            'metric' => Metric::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, ValidationServices $validationServices)
    {
        $res = $validationServices->validateMetricUpdateDetails($request, $id);
        $res['step'] /= 100;
        Metric::findOrFail($id)->update($res);
        return to_route('metrics.show', ['metric' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Metric::findOrFail($id)->delete();
        return to_route('metrics.index');
    }
}
