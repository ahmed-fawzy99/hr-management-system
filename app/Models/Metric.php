<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Metric extends Model
{
    use HasFactory, LogsActivity;
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function findGrade(float $value): string
    {
        $res = [
            'Extremely Poor' => 0,
            'Very Poor' => 0,
            'Poor' => 0,
            'Neutral' => 0,
            'Good' => 0,
            'Very Good' => 0,
            'Excellent' => 0,
        ];
        $keys = array_keys($res);
        $k = 0;
        for ($i = -3; $i <= 3; $i++) {
            $res[$keys[$k]] = 1 + floatval($this->step) * $i;

            if($res[$keys[$k]] == $value){
                return $keys[$k];
            }
            $k++;
        }
        return 'NULL';
    }
}
