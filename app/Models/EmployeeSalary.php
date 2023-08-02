<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class EmployeeSalary extends Model
{
    use LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // This function should be in a helper, not in this model because it might be used somewhere else.
    function getCurrencySymbol()
    {
        $currencySymbols = [
            'EGP' => 'E£',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'CAD' => '$',
            'KWD' => 'د.ك',
            'SAR' => '﷼',
            'AED' => 'د.إ'
        ];

        return $currencySymbols[$this->currency] ?? '';
    }
}
