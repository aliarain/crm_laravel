<?php

namespace App\Helpers\CoreApp\Traits;

use App\Models\Hrm\Shift\Shift;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Role\RoleUser;
use App\Models\Hrm\Department\Department;

trait TimeDurationTrait
{

    protected function convertSecondToHour($startTime, $endTime)
    {
        return $endTime->diffInSeconds($startTime) / 3600;
    }

    public function dateTimeInAmPm($time): string
    {
        $userCountry = auth()->user()->company->country;
        $now = Carbon::now();
        return $now->parse($time)->format('g:i A');
    }

    public function timeDifference($start, $end = null): string
    {
        $startTime = Carbon::parse($start);
        $endTime = Carbon::parse($end);
        $totalDuration = $startTime->diff($endTime)->format("%H Hr's:%I min");

        return $totalDuration;
    }
    public function timeDifferenceHour($start, $end = null): string
    {
        $startTime = Carbon::parse($start);
        $endTime = Carbon::parse($end);
        $totalDuration = $startTime->diff($endTime)->format("%H");

        return $totalDuration;
    }

    public function totalTimeDifference($start, $end): string
    {
        $startTime = Carbon::parse($start);
        $endTime = Carbon::parse($end);
        $diff = $endTime->diffInSeconds($startTime);
        $hours = ($diff / 3600);
        return $hours;
    }

    public function overTimeCount($attendance)
    {
        $startTime = strtotime($attendance->check_in);
        $endTime = strtotime($attendance->check_out);

        $dutySchedule = Shift::where('id', $attendance->user->shift_id)->with('dutySchedule')->first();
        $workingHour = @$dutySchedule->dutySchedule->hour;
        $totalSeconds = ($endTime - $startTime ) - $workingHour*3600;
        $hours = (int) floor($totalSeconds / 3600);
        $minutes = (int) floor(($totalSeconds / 60) % 60);
        //$seconds = $totalSeconds % 60;

        if($hours > 0){
            // hour greater than 1 it will be plural
            $hours = $hours.' '.Str::plural('hr', $hours);
            if($minutes >0){
                $minutes = $minutes.' '.Str::plural('min', $minutes);
                return $hours . ', '.$minutes;
            }else{
                return $hours;
            }
        }else{
            if ($minutes>0){
                $minutes = $minutes.' '.Str::plural('min', $minutes);
                return $minutes;
            }
        }
    }

    public function timeToSeconds(string $time): int
    {
        $arr = explode(':', $time);
        if (count($arr) === 3) {
            return $arr[0] * 3600 + $arr[1] * 60 + $arr[2];
        }
        return $arr[0] * 60 + $arr[1];
    }

    public function totalSpendTime($start, $end)
    {
        $time = abs($end - $start);
        $formatedTime = Carbon::parse($time)->format("H:i:s");
        return $formatedTime;
    }

    public function hourOrMinute($start, $end)
    {
        $startTime = strtotime($start);
        $endTime = strtotime($end);
        $totalSeconds = $endTime - $startTime;
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds / 60) % 60);
        $seconds = $totalSeconds % 60;
        if($hours > 0){
            // hour greater than 1 it will be plural
            $hours = $hours.' '.Str::plural('hr', $hours);
            if($minutes >0){
                $minutes = $minutes.' '.Str::plural('min', $minutes);
            return $hours . ', '.$minutes;
            }else{
                return $hours;
            }
        }else{
            $minutes = $minutes.' '.Str::plural('min', $minutes);
            return $minutes;
        }
    }

    public function hourMinSecond($start){
        $start  = new Carbon(date('Y-m-d'). ' '.$start);
        $end    = Carbon::now();
       return $start->diff($end)->format('%H:%I:%S');
    }

}

