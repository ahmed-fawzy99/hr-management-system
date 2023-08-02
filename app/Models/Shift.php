<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Shift extends Model
{
    use LogsActivity;
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function employees(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Employee::class, EmployeeShift::class,
            'shift_id', 'id', 'id', 'employee_id')
            ->where('employee_shifts.end_date', null);
    }

    public function shiftPeriod(): float|int
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        if ($end->lessThan($start)) {
            $end->addDay(); // Add 24 hours to the end time to prevent the midnight bug.
        }
        $duration = $end->diff($start);
        return $duration->h + ($duration->i / 60); // returns # hours as a float
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value
                . ' (' .
                Carbon::parse($this->start_time)->format('g:i A')
                . ' - ' .
                Carbon::parse($this->end_time)->format('g:i A')
                . ')',
        );
    }
}
