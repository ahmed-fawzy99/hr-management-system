<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Rules\CustomIPValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Propaganistas\LaravelPhone\Rules\Phone;
use Intervention\Validation\Rules\Iban;

use Session;

// You should rename "Verifiers" to "Validate"
Class ValidationServices extends Controller {

    protected $validationMessages = [
        'phone' => 'This phone number format is not valid.',
        'currency' => 'There seems to be an issue with your selected currency. Have you selected one?',
        'role' => 'Invalid Role',
    ];

    protected array $roles = [
        'admin',
        'operation_manager',
        'project_manager',
        'accountant',
        'hr',
        'employee',
    ];
    public function validateEmployeeCreationDetails($request)
    {
        return $request->validate([
            'name' => ['required', 'unique:employees', 'max:50'],
            'email' => ['required','unique:employees', 'email:strict'],
//            'national_id' => ['required', 'unique:employees', 'min:14', 'max:14'], // Egypt's national ID is 14 digits
            'national_id' => ['required', 'unique:employees'],
            'phone' => ['required', 'regex:/(^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$)/', 'unique:employees'],
            'hired_on' => ['nullable', 'date_format:Y-m-d'],
            'address' => ['required', 'string', 'max:255'],
            'bank_acc_no' => ['nullable', 'iban'],
            'branch_id' => ['required', 'integer'],
            'department_id' => ['required', 'integer'],
            'is_remote' => ['required', 'boolean'],

            'shift_id' => ['required', 'integer'],
            'position_id' => ['required', 'integer'],
            'currency' => ['required'],
            'salary' => ['required','integer'],
            'role' => ['required', Rule::in($this->roles)],

        ], $this->validationMessages);
    }

    public function validateEmployeeUpdateDetails($request, $id)
    {
        //
        return $request->validate([
            'name' => ['required', 'unique:employees,name,'.$id, 'max:50'],
            'email' => ['required','unique:employees,email,'.$id, 'email:strict'],
//            'national_id' => ['required', 'unique:employees,national_id,'.$id, 'min:14', 'max:14'], // Egypt's national ID is 14 digits
            'national_id' => ['required', 'unique:employees,national_id,'.$id],
            'phone' => ['required', 'regex:/(^[\+]??[0-9]{3}?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$)/', 'unique:employees,phone,'.$id],
            'hired_on' => ['nullable', 'date'],
            'address' => ['required', 'string', 'max:255'],
            'bank_acc_no' => ['iban', 'nullable'],
            'branch_id' => ['required', 'integer'],
            'department_id' => ['required', 'integer'],
            'is_remote' => ['required', 'boolean'],

            'shift_id' => ['required', 'integer'],
            'position_id' => ['required', 'integer'],
            'currency' => ['required'],
            'salary' => ['required','integer'],
            'role' => ['required', Rule::in($this->roles)],
        ], $this->validationMessages);
    }
    public function validateShiftIdDetails($request)
    {
        return $request->validate([
            'shift_id' => ['nullable', 'integer'],
        ], $this->validationMessages);
    }
    public function validatePositionIdDetails($request)
    {
        return $request->validate([
            'position_id' => ['nullable', 'integer'],
        ], $this->validationMessages);
    }
    public function validateEmployeeSalaryDetails($request)
    {
//        dd($request-);
        return $request->validate([
            'currency' => ['required'],
            'salary' => ['required','integer'],
        ], $this->validationMessages);
    }
    public function validatePositionCreationDetails($request)
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:positions'],
            'description' => ['nullable', 'string'],
        ], $this->validationMessages);
    }
    public function validatePositionUpdateDetails($request, $id)
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:positions,name,'.$id],
            'description' => ['nullable', 'string'],
        ], $this->validationMessages);
    }
    public function validateDepartmentCreationDetails($request)
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments'],
            'manager_id' => ['nullable', 'integer'],
        ], $this->validationMessages);
    }
    public function validateDepartmentUpdateDetails($request, $id)
    {
        //             'name' => ['required', 'string', 'max:255', 'unique:branches,name,'.$id],
        return $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name,'.$id],
            'manager_id' => ['nullable', 'integer'],
        ], $this->validationMessages);
    }
    public function validateEmployeeRoleDetails($request)
    {
        return $request->validate([
            'role' => ['required', Rule::in($this->roles)],
        ], $this->validationMessages);
    }
    public function validateBranchCreationDetails($request)
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:branches'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/(^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$)/'],
            'email' => ['required', 'email:strict'],
            'manager_id' => ['nullable', 'integer'],
        ], $this->validationMessages);
    }
    public function validateBranchUpdateDetails($request, $id)
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:branches,name,'.$id],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/(^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$)/'],
            'email' => ['required', 'email:strict'],
            'manager_id' => ['nullable', 'integer'],
        ], $this->validationMessages);
    }

    public function validateShiftCreationDetails($request)
    {
        return $request->validate([
            'start_time' => ['required', 'array', 'size:3', 'filled'],
            'start_time.hours' => ['required', 'numeric'],
            'start_time.minutes' => ['required', 'numeric'],
            'start_time.seconds' => ['required', 'numeric'],

            'end_time' => ['required', 'array', 'size:3', 'filled'],
            'end_time.hours' => ['required', 'numeric'],
            'end_time.minutes' => ['required', 'numeric'],
            'end_time.seconds' => ['required', 'numeric'],

            'name' => ['required', 'string', 'unique:shifts', 'max:255'],
            'shift_payment_multiplier' => ['nullable', 'numeric', 'min:1'],
            'description' => ['nullable', 'string'],

        ], $this->validationMessages);
    }
    public function validateShiftUpdateDetails($request, $id)
    {
        return $request->validate([
            'start_time' => ['required', 'array', 'size:3', 'filled'],
            'start_time.hours' => ['required', 'numeric'],
            'start_time.minutes' => ['required', 'numeric'],
            'start_time.seconds' => ['required', 'numeric'],

            'end_time' => ['required', 'array', 'size:3', 'filled'],
            'end_time.hours' => ['required', 'numeric'],
            'end_time.minutes' => ['required', 'numeric'],
            'end_time.seconds' => ['required', 'numeric'],

            'name' => ['required', 'string', 'unique:shifts,name,'.$id, 'max:255'],
            'shift_payment_multiplier' => ['nullable', 'numeric', 'min:1'],
            'description' => ['nullable', 'string'],

        ], $this->validationMessages);
    }
    public function validateShiftUpdateDetailsNotArray($request, $id)
    {
        return $request->validate([
            'start_time' => ['required', 'date_format:H:i:s'],
            'end_time' => ['required', 'date_format:H:i:s'],
            'name' => ['required', 'string', 'unique:shifts,name,'.$id, 'max:255'],
            'shift_payment_multiplier' => ['nullable', 'numeric', 'min:1'],
            'description' => ['nullable', 'string'],

        ], $this->validationMessages);
    }

    public function validateOrgDetailsUpdateData($request)
    {
        return $request->validate([
            'organization_name' => ['required','max:255'],
            'timezone' => ['required','timezone'],
            'email' => ['required', 'email:strict'],
            'organization_address' => ['required', 'string', 'max:255'],
            'absence_limit' => ['required', 'numeric', 'min:0', 'max:365'],
            'payroll_day' => ['required', 'integer', 'min:1', 'max:31'],
            'weekend_off_days' => ['nullable', 'array', 'max:7'],
            'weekend_off_days.*' => ['nullable', 'string', 'in:saturday,sunday,monday,tuesday,wednesday,thursday,friday'],
            'income_tax' => ['required', 'numeric', 'min:0', 'max:100'],
            'is_ip_based' => ['required', 'boolean'],

            // Conditional validation. If is_ip_based is true, then ip is required and must be an IP address, nullable otherwise.
            // Laravel Already has 'ip' validation rule, but it does not support wildcards. So, I have created a custom rule for that.
            'ip' => $request->get('is_ip_based', false) ?  ['required' , 'array'] : ['nullable'],
            'ip.*' => $request->get('is_ip_based', false) ?  ['required' , new CustomIPValidator()] : ['nullable'],
        ], $this->validationMessages);

    }
    public function validateRequestCreationDetails($request)
    {
        return $request->validate([
            'type' => ['required', 'string', 'in:complaint,payment,leave,other'],
            'date' => ['array','size:2'],
            'date.*' => ['nullable', 'date_format:Y-m-d'],
            'message' => ['nullable', 'string'],
        ], $this->validationMessages);
    }

    public function validateMassAttendanceCreation($request)
    {
        $rules = [
            'date' => 'required|date',
            'employee_id' => 'required|array',
            'employee_id.*' => 'required|integer',
            'status' => 'required|array',
            'status.*' => 'required|in:on_time,late,missed',
            'sign_in_time' => 'required|array',
            'sign_in_time.*' => 'required|array|size:3',
            'sign_in_time.*.hours' => 'required|integer',
            'sign_in_time.*.minutes' => 'required|integer',
            'sign_in_time.*.seconds' => 'nullable|integer',
            'sign_off_time' => 'required|array',
            'sign_off_time.*' => 'required|array|size:3',
            'sign_off_time.*.hours' => 'required|integer',
            'sign_off_time.*.minutes' => 'required|integer',
            'sign_off_time.*.seconds' => 'required|integer',
            'notes' => 'required|array',
            'notes.*' => 'nullable',
        ];

        $validatedData = $request->validate($rules);

        $arraySizes = collect($validatedData)
            ->except(['date'])
            ->reject(fn ($value) => is_array($value) && count($value) === count($validatedData['employee_id']))
            ->keys();

        if ($arraySizes->isNotEmpty()) {
            $errorMessage = 'Arrays have different sizes.';

            // Optionally, you can append the field names with different sizes to the error message
            $errorMessage .= ' Fields: ' . $arraySizes->implode(', ');

            // You can choose how to handle the error, such as returning a response or throwing an exception
            return response()->json(['error' => $errorMessage], 422);
            // Or throw an exception: throw new \InvalidArgumentException($errorMessage);
        }
        return $validatedData;
    }
    public function validateCalendarItemCreationDetails($request)
    {
        return $request->validate([
            'date' => ['array','size:2'],
            'date.*' => ['nullable', 'date_format:Y-m-d'],
            'title' => ['required', 'string'],
            'type' => ['required', 'string', 'in:holiday,meeting,event,other'],
        ], $this->validationMessages);
    }
    public function validateYearDayCreationDetails($request)
    {
        return $request->validate([
            'year' => ['required','integer', 'min:2000', 'max:2099'],
            'saturday' => ['required', 'min:52', 'max:53'],
            'sunday' => ['required', 'min:52', 'max:53'],
            'monday' => ['required', 'min:52', 'max:53'],
            'tuesday' => ['required', 'min:52', 'max:53'],
            'wednesday' => ['required', 'min:52', 'max:53'],
            'thursday' => ['required', 'min:52', 'max:53'],
            'friday' => ['required', 'min:52', 'max:53'],
        ], $this->validationMessages);
    }
    public function validatePayrollReviewDetails($request)
    {
        $rules = [

            // Quick Pay
            'quick_pay' => ['required','boolean'],
            'quick_pay_send_email' => ['required','boolean'],

            // Additions
            'rewards' => ['nullable','numeric', 'min:0'],
            'incentives' => ['nullable','numeric', 'min:0'],
            'reimbursements' => ['nullable','numeric', 'min:0'],
            'shift_differentials' => ['nullable','numeric', 'min:0'],
            'commissions' => ['nullable','numeric', 'min:0'],
            'extra_hour_rate' => ['nullable','numeric', 'min:0'],

            // Deductions
            'social_security_contributions' => ['nullable','numeric', 'min:0'],
            'health_insurance' => ['nullable','numeric', 'min:0'],
            'retirement_plan' => ['nullable','numeric', 'min:0'],
            'benefits' => ['nullable','numeric', 'min:0'],
            'union_fees' => ['nullable','numeric', 'min:0'],
            'negative_hour_rate' => ['nullable','numeric', 'min:0'],

            // Metric Multiplier
            'metricsIDs' => ['required','array'],
            'metricsIDs.*' => ['required','integer', 'min:1'],
            'metrics' => ['required','array'],
            'metrics.*' => ['required','numeric'],
            'performance_multiplier' => ['nullable','numeric', 'min:0'],
        ];

        $validatedData = $request->validate($rules);


        if (count($validatedData['metricsIDs']) != count($validatedData['metrics'])) {
            $errorMessage = 'Arrays have different sizes.';
            return response()->json(['error' => $errorMessage], 422);
        }
        return $validatedData;
    }
    public function validateMetricCreationDetails($request)
    {
        return $request->validate([
            'criteria' => ['required','unique:metrics', 'string', 'max:255'],
            'weight' => ['required','numeric', 'min:0', 'max:10'],
            'step' => ['required','numeric', 'min:0.1', 'max:100'],
        ], $this->validationMessages);
    }
    public function validateMetricUpdateDetails($request, $id)
    {
        return $request->validate([
            'criteria' => ['required','unique:metrics,criteria,'.$id, 'string', 'max:255'],
            'weight' => ['required','numeric', 'min:0', 'max:10'],
            'step' => ['required','numeric', 'min:0.1', 'max:100'],
        ], $this->validationMessages);
    }
    public function validateDayAttendanceDateParameter($day)
    {
        $validator = Validator::make(['dateParameter' => $day], [
            'dateParameter' => 'required|string|date_format:Y-m-d',
        ]);
        if ($validator->fails())
            return response()->json([$validator->errors()]);

        return $validator->validated()['dateParameter'];
    }

    public function validatePayrollIndexParams($request)
    {
        $request->validate([
            'date' => ['nullable', 'array', 'size:2'],
            'date.month' => ['nullable', 'integer', 'min:0', 'max:11'],
            'date.year' => ['nullable', 'integer', 'min:1453', 'max:2999'],
            'status' => ['nullable', 'string', 'in:all,pending,reviewed,paid'],
        ]);
    }

}
