<?php

namespace App\Http\Controllers;

use App\Models\Globals;
use App\Models\Metric;
use App\Models\Payroll;
use App\Services\CommonServices;
use App\Services\PayrollServices;
use App\Services\ValidationServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PayrollController extends Controller
{
    protected PayrollServices $payrollServices;
    protected ValidationServices $validationServices;
    public function __construct()
    {
        $this->payrollServices = new PayrollServices;
        $this->validationServices = new ValidationServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->validationServices->validatePayrollIndexParams($request);

        // Setting up filters, if any.
        $dateParam = $request->input('date', '');
        $statusParam = $request->input('status', '');

        if ($dateParam) {
            $date = Carbon::createFromDate($request->date['year'], $request->date['month'], Carbon::now()->startOfMonth()->format('j'));
            if ($date->isAfter(Carbon::today())) {
                return response()->json(['Error' => 'Date cannot be in the future. Go back and choose a date before today.'], 400);
            }
            $date = $date->toDateString();
        } else {
            $date = '';
        }

        // Main Query
        $payrolls = Payroll::leftJoin('employees', 'payrolls.employee_id', '=', 'employees.id')
            ->select('payrolls.id', 'due_date', 'currency', 'total_payable', 'employees.name as employee_name', 'status', 'is_reviewed')
            ->orderBy('id');

        // Limit to logged-in employee if not admin
        if (!isAdmin())
            $payrolls->where('payrolls.employee_id', auth()->user()->id);

        // Date Filter
        if ($date)
            $payrolls->whereYear('due_date', $request->date['year'])->whereMonth('due_date', $request->date['month'] + 1);

        // Status Filter
        if ($statusParam == 'pending') {
            $payrolls->where('status', false)->where('is_reviewed', false);
        } else if ($statusParam == 'reviewed') {
            $payrolls->where('status', false)->where('is_reviewed', true);
        } else if ($statusParam == 'paid') {
            $payrolls->where('status', true);
        }

        return Inertia::render('Payroll/Payrolls', [
            'payrolls' => $payrolls->paginate(config('constants.data.pagination_count')),
            "dateParam" => $date,
            "statusParam" => $statusParam,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        authenticateIfNotAdmin(auth()->user()->id, $id);
        $payroll = Payroll::with('employee')->find($id);
        if ($payroll) {
            return Inertia::render('Payroll/PayrollView', [
                'payroll' => $payroll,
            ]);
        } else {
            return redirect()->back()->withErrors(['end_of_payrolls' => 'No More Payrolls to Show']);
        }
    }

    /**
     * Payroll Review Page.
     */
    public function edit(string $id)
    {
        $payroll = Payroll::with('employee')->findOrFail($id);
        $payrollDate = Carbon::parse($payroll->due_date)->subMonthNoOverflow();

        $commonServices = new CommonServices();
        $dates = [$payrollDate->year, $payrollDate->month, 1, $payrollDate->year, $payrollDate->month, $payrollDate->daysInMonth];
        return Inertia::render('Payroll/PayrollReview', [
            'payroll' => $payroll,
            "month_stats" => $commonServices->getMonthStats($payroll->employee, $dates),
            'additions' => $payroll->additions, // PLACEHOLDER CODE. MUCH MORE WORK NEEDED HERE
            'deductions' => $payroll->deductions, // PLACEHOLDER CODE. MUCH MORE WORK NEEDED HERE
            'income_tax' => Globals::select('income_tax')->get()->first(), // PLACEHOLDER CODE. MUCH MORE WORK NEEDED HERE
            'shift_modifier' => $payroll->employee->activeShift()->shift_payment_multiplier,
            'hours' => $payroll->employee->monthHours($payrollDate->year, $payrollDate->month),
            'metrics' => Metric::where('created_at', '<=', $payroll->created_at)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $res = $this->validationServices->validatePayrollReviewDetails($request);
        return $this->payrollServices->updatePayroll($res, $id);
    }

    /**
     * Update Status.
     */
    public function updateStatus(Request $request, string $id)
    {
        $this->payrollServices->updatePayrollStatus($request, $id);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Payroll::findOrFail($id)->delete();
    }
}
