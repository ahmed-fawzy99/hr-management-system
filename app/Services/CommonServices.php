<?php

namespace App\Services;

use App\Http\Controllers\Controller;

use App\Models\Attendance;
use App\Models\Calendar;
use App\Models\Globals;
use App\Models\Manager;
use Carbon\Carbon;
use Session;

// You should rename "Verifiers" to "Validate"
class CommonServices extends Controller
{
    public function setManager(int $manager_id, int $thing_id, bool $thing_type): void
    {
        // thing_type: 0 for branch, 1 for department
        Manager::create([
            'employee_id' => $manager_id,
            'branch_id' => $thing_type ? null : $thing_id,
            'department_id' => $thing_type ? $thing_id : null,
        ]);
    }

    public function updateManager(int $manager_id, int $thing_id, bool $thing_type): void
    {
        // thing_type: 0 for branch, 1 for department
        Manager::updateOrCreate(
            [
                'branch_id' => $thing_type ? null : $thing_id,
                'department_id' => $thing_type ? $thing_id : null,
            ],
            [
                'employee_id' => $manager_id,
                'branch_id' => $thing_type ? null : $thing_id,
                'department_id' => $thing_type ? $thing_id : null,
            ]);
    }


    public function removeManager(int $thing_id, bool $thing_type): void
    {
        // thing_type: 0 for branch, 1 for department
        $manager = Manager::where([
            'branch_id' => $thing_type ? null : $thing_id,
            'department_id' => $thing_type ? $thing_id : null,
        ]);
        if ($manager->exists()){
            $manager->first()->delete();
        }
    }

    //  $monthDates = [startMonth, startDay, endMonth, endDay]
    public function calcOffDays($weekendOffDays, $hireDate, $monthDates = [0, 1, 1, 0, 12, 31]): int|float|array
    {
        $mode = "custom";
        $weekendOffDays = array_map(function ($day) {
            return strtolower($day);
        }, $weekendOffDays);

        if ($monthDates[0] === 0 || $monthDates[3] === 0) {
            $monthDates[0] = $monthDates[3] = Carbon::now()->year;
            $mode = "year";
        }

        $hireData = Carbon::parse($hireDate);
        $startDate = Carbon::createFromDate($monthDates[0], $monthDates[1], $monthDates[2]);
        $endDate = Carbon::createFromDate($monthDates[3], $monthDates[4], $monthDates[5]);

        if ($hireData->isAfter($startDate)) {
            $startDate = Carbon::createFromDate($hireData->year, $hireData->month, $hireData->day);
        }

        // Clone keyword passes data by value. The default in PHP is by reference.
        $startDate2 = clone $startDate;
        $dayCounts = [];

        foreach ($weekendOffDays as $day) {
            $dayCounts[$day] = 0;
        }

        while ($startDate <= $endDate) {
            $dayOfWeek = strtolower($startDate->format('l'));

            if (in_array($dayOfWeek, $weekendOffDays)) {
                $dayCounts[$dayOfWeek]++;
            }
            $startDate->addDay();
        }

        if ($mode !== "year") {
            return array_sum($dayCounts);
        }

        $total_days = $startDate2->diffInDays($endDate) + 1;
        return [
            "offDays" => array_sum($dayCounts),
            "total_year_days" => $total_days,
            "total_weekend_days" => $total_days - array_sum($dayCounts),
        ];
    }


    /********************************************************************
     * By default, this function will count holidays in the current year.
     * You can pass $dates to count holidays in a specific range.
     * $HireDate = "YYYY-MM-DD"
     * $monthDates = [startYear, startMonth, startDay,
     *                endYear, endMonth, endDay]
     ********************************************************************/
    public function countHolidays($hireDate, $dates = null): int
    {
        if ($dates === null) {
            $dates = [
                Carbon::now()->year, 1, 1,   // Start date default: January 1 of current year
                Carbon::now()->year, 12, 31  // End date default: December 31 of current year
            ];
        }

        $startDate = Carbon::createFromDate($dates[0], $dates[1], $dates[2]);
        $endDate = Carbon::createFromDate($dates[3], $dates[4], $dates[5]);

        $holidaysArray =  Calendar::where([
            ['type', 'holiday'],
            ['start_date', '>', $hireDate],
        ])->whereBetween('start_date', [$startDate, $endDate])
            ->select(['start_date', 'end_date'])->get()->toArray();

        return $this->countHolidayDateRanges($holidaysArray);
    }

    public function countHolidayDateRanges($holidaysArray): int
    {
        $totalDays = 0;
        foreach ($holidaysArray as $entry) {
            $startDate = Carbon::parse($entry['start_date']);
            $endDate = $entry['end_date'] ? Carbon::parse($entry['end_date']) : null;
            if ($endDate === null) {
                $totalDays += 1;
            } else {
                $daysDifference = $endDate->diffInDays($startDate) + 1;
                $totalDays += $daysDifference;
            }
        }
        return $totalDays;
    }

    // $dateArr: [month_year, month, month_start, month_year, month, month_start] - 2 repeated fields #month_year & month
    // Just want to avoid doing logic of modifying the array for calcOffDays() call.
    public function getMonthStats($employee, $dateArr){
        $globalSettings = Globals::first();
        return [
            'attendable_days' => $dateArr[5] - $this->calcOffDays(json_decode($globalSettings->weekend_off_days),
                    $employee->hired_on, $dateArr),
            'attended' => Attendance::where('employee_id', $employee->id)
                ->whereYear('date', $dateArr[0])
                ->whereMonth('date', $dateArr[1])
                ->where('status', 'on_time')
                ->count(),
            'absented' => Attendance::where('employee_id', $employee->id)
                ->whereYear('date', $dateArr[0])
                ->whereMonth('date', $dateArr[1])
                ->where('status', 'missed')
                ->count(),
            'late' => Attendance::where('employee_id', $employee->id)
                ->whereYear('date', $dateArr[0])
                ->whereMonth('date', $dateArr[1])
                ->where('status', 'late')
                ->count(),
        ];
    }

    public function isHoliday($date){
        return Calendar::where('type', 'holiday')
            ->where(function ($query) use ($date) {
                $query->where(function ($query) use ($date) {
                    $query->whereDate('start_date', '<=', $date)
                        ->whereDate('end_date', '>=', $date);
                })
                    ->orWhere(function ($query) use ($date) {
                        $query->whereDate('start_date', '=', $date)
                            ->whereNull('end_date');
                    });
            })
            ->exists();
    }

    public function isWeekend($date): bool
    {
        $weekendDays = json_decode(Globals::first()->weekend_off_days);
        $today = Carbon::parse($date)->englishDayOfWeek; // Get the current day of the week in English
        return in_array(strtolower($today), array_map('strtolower', $weekendDays));
    }

    public function isDayOff($date): bool
    {
        return $this->isHoliday($date) || $this->isWeekend($date);
    }
    public function isTodayOff(): bool
    {
        $date = Carbon::now()->toDateString();
        return $this->isHoliday($date) || $this->isWeekend($date);
    }

}
