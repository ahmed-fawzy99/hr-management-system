<?php

namespace App\Services;


use App\Mail\PayrollEmail;
use App\Mail\RequestStatusUpdated;
use App\Models\Globals;
use App\Models\Metric;
use App\Models\Payroll;
use Arr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class PayrollServices
{
    private function quickPay($payroll, $res)
    {
        $payroll->additions->update([
            'status' => true,
        ]);
        $payroll->deductions->update([
            'status' => true,
        ]);
        $payroll->update([
            'performance_multiplier' => 1,
            'total_deductions' => 0,
            'total_additions' => 0,
            'total_payable' => $payroll->base,
            'is_reviewed' => true,
            'status' => true, // paid
        ]);
        if ($res['quick_pay_send_email']) {
            Mail::to($payroll->employee->email)->queue(new PayrollEmail($payroll));
        }
        return to_route('payrolls.show', ['payroll' => $payroll]);
    }
    public function updatePayroll($res, $id)
    {
        $payroll = Payroll::findOrFail($id);

        if ($res['quick_pay']) {
            return $this->quickPay($payroll, $res);
        }

        $shiftDifferentials = ($payroll->employee->activeShift()->shift_payment_multiplier - 1) * $payroll->base;
        $hours = $payroll->employee->monthHours(Carbon::parse($payroll->due_date)->subMonthNoOverflow()->year,
            Carbon::parse($payroll->due_date)->subMonthNoOverflow()->month);
        $overtime = $hours['hoursDifference'] > 0 ? $hours['hoursDifference'] * $res['extra_hour_rate'] : 0;
        $undertime = $hours['hoursDifference'] < 0 ? $hours['hoursDifference'] * $res['negative_hour_rate'] * -1 : 0; // * -1 to get +ve value, as hoursDifference is -ve
        $income_tax = (Globals::first()->income_tax / 100) * $payroll->base;

        $payroll->additions->update([
            'rewards' => $res['rewards'],
            'incentives' => $res['incentives'],
            'reimbursements' => $res['reimbursements'],
            'shift_differentials' => $shiftDifferentials,
            'commissions' => $res['commissions'],
            'overtime' => $overtime,
            'extra_hour_rate' => $res['extra_hour_rate'],
            'status' => true,
        ]);

        $payroll->deductions->update([
            'income_tax' => $income_tax,
            'social_security_contributions' => $res['social_security_contributions'],
            'health_insurance' => $res['health_insurance'],
            'retirement_plan' => $res['retirement_plan'],
            'benefits' => $res['benefits'],
            'union_fees' => $res['union_fees'],
            'undertime' => $undertime,
            'negative_hour_rate' => $res['negative_hour_rate'],
            'status' => true,
        ]);

        for ($i = 0; $i < count($payroll->evaluations); $i++) {
            $ev = $payroll->evaluations[$i];
            $grade = $ev->metric->findGrade($res['metrics'][$i]);
            $payroll->evaluations[$i]->where([
                'metric_id' => $res['metricsIDs'][$i],
                'payroll_id' => $payroll->id,
                'employee_id' => $payroll->employee_id,
            ])->update([
                // [GRADE - WEIGHT - STEP - WEIGHTED POINT]
                'score' => [$grade, $ev->metric->weight, $ev->metric->step,
                    round(1 + (abs(1 - $res['metrics'][$i]) * $ev->metric->weight * ($res['metrics'][$i] > 1 ? 1 : -1)), 2)],
            ]);
        }

        $payroll->update([
            'performance_multiplier' => $res['performance_multiplier'],
            'total_deductions' => $payroll->deductions->getSum(),
            'total_additions' => $payroll->additions->getSum(),
            'total_payable' => $payroll->base * $res['performance_multiplier'] + $payroll->additions->getSum() - $payroll->deductions->getSum(),
            'is_reviewed' => true,
        ]);

        return to_route('payrolls.show', ['payroll' => $payroll]);


    }

    public function updatePayrollStatus($request, $id){
        $request->validate([
            'status' => 'required|boolean',
            'sendEmail' => 'nullable|integer|in:0,1'
        ]);
        $payroll = Payroll::findOrFail($id);
        if (!$payroll->is_reviewed) {
            return response()->json(['Error' => 'Payroll must be reviewed before it can be paid.']);
        }
        $payroll->update([
            'status' => $request->status,
        ]);

        if ($request->sendEmail && $payroll->status) {
            Mail::to($payroll->employee->email)->queue(new PayrollEmail($payroll));
        }
    }
    public function renderIndexPage($request): \Inertia\Response
    {
        $request->validate([
            'date' => ['nullable', 'array', 'size:2'],
            'date.month' => ['nullable', 'integer', 'min:0', 'max:11'],
            'date.year' => ['nullable', 'integer', 'min:1453', 'max:2999'],
            'status' => ['nullable', 'string', 'in:all,pending,reviewed,paid'],
        ]);

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

        if (!isAdmin()) {
            $payrolls->where('payrolls.employee_id', auth()->user()->id);
        }

        // Date Filter
        if ($date) {
            $payrolls->whereYear('due_date', $request->date['year'])->whereMonth('due_date', $request->date['month'] + 1);
        }

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
    public function renderShowPage($id): \Inertia\Response
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
    public function renderReviewPage($id): \Inertia\Response
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

}
