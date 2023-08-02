<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Payroll extends Model
{
    use HasFactory, LogsActivity;
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function additions(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Addition::class);
    }
    public function deductions(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Deduction::class);
    }

    public function evaluations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EmployeeEvaluation::class);
    }

//    protected function status(): Attribute
//    {
//        return Attribute::make(
//            get: fn (bool $value) => match ($value) {
//                true => 'Paid',
//                false => 'Pending',
//            },
//        );
//    }

}
