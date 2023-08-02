<?php

namespace App\Http\Controllers;

use App\Mail\PayrollEmail;
use App\Models\Globals;
use App\Models\Metric;
use App\Models\Payroll;
use App\Services\CommonServices;
use App\Services\PayrollServices;
use App\Services\ValidationServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class PayrollController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PayrollServices $payrollServices)
    {
        return $payrollServices->renderIndexPage($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){}

    /**
     * Display the specified resource.
     */
    public function show(string $id, PayrollServices $payrollServices)
    {
        return $payrollServices->renderShowPage($id);
    }

    /**
     * Payroll Review Page.
     */
    public function edit(string $id, PayrollServices $payrollServices)
    {
        return $payrollServices->renderReviewPage($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, ValidationServices $validationServices, PayrollServices $payrollServices)
    {
        $res = $validationServices->validatePayrollReviewDetails($request);
        return $payrollServices->updatePayroll($res, $id);
    }



    /**
     * Update Status.
     */
    public function updateStatus(Request $request, string $id, PayrollServices $payrollServices)
    {
        $payrollServices->updatePayrollStatus($request, $id);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Payroll::findOrFail($id)->delete();
    }
}
