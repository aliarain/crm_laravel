<?php

namespace App\Helpers\CoreApp\General;

use App\Models\Hrm\Department\Department;
use Carbon\Carbon;

class DateTimeParser
{
    protected function convertSecondToHour($startTime, $endTime)
    {
        return $endTime->diffInSeconds($startTime) / 3600;
    }

    public function dateTimeInAmPm($time): string
    {
        $now = Carbon::now();
        return $now->parse($time)->format('g:i A');
    }

    public function timeDifference($start, $end): string
    {
        $startTime = Carbon::parse($start);
        $endTime = Carbon::parse($end);
        $totalDuration = $startTime->diff($endTime)->format('%H:%I') . " min";

        return $totalDuration;
    }

    public function overTimeCount($attendance)
    {
        $startTime = strtotime($attendance->check_in);
        $endTime = strtotime($attendance->check_out);

        $totalDurationTime = (($endTime - $startTime) / 3600);

        $dutySchedule = Department::where('id', $attendance->user->department_id)->with('dutySchedule')->first();
        $hour = 0;
        $hour = @$dutySchedule->dutySchedule->hour;
        $overtime = 0;
        if ($totalDurationTime > $hour) {
            return number_format(abs($totalDurationTime - $hour), 2) . " min";
        } else {
            return null;
        }
    }
    function dateDiff($date1, $date2)
    {
        $date1_ts = strtotime($date1);
        $date2_ts = strtotime($date2);
        $diff = $date2_ts - $date1_ts;
        return round($diff / 86400) + 1;
    }

    function getMonthDateStringWithTime($date): string
    {
        $new = Carbon::parse($date);
        return $new->format('M d g:i A');
    }

    function getMonthDate($date): string
    {
        $new = Carbon::parse($date);
        return $new->format('M d');
    }

    public static function getCurrentMonthDays(): array
    {
        $date = Carbon::now();
        $startOfMonth = $date->copy()->startOfMonth()->subDay();
        $endOfMonth = $date->copy()->endOfMonth()->format('d');
        $monthDays = [];

        for ($i = 0; $i < $endOfMonth; $i++) {
            $monthDays[] = $startOfMonth->addDay()->startOfDay()->copy();
        }

        return $monthDays;
    }

}