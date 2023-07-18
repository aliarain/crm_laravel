<?php

namespace App\Helpers\CoreApp\General;

use App\Enums\AttendanceStatus;
use App\Models\Hrm\Attendance\DutySchedule;
use App\Models\User;

class CheckinCheckoutStatus
{

    public function checkInStatus($user_id, $check_in_time): array
    {
        /*
         *  OT = On time
         * E = Early
         * L = Late
         */

        $user_info = User::find($user_id);
        $schedule = DutySchedule::where('shift_id', $user_info->shift_id)->where('status_id', 1)->first();
        if ($schedule) {
            $startTime = strtotime($schedule->start_time);
            $check_in_time = strtotime($check_in_time);
            $diffFromStartTime = ($check_in_time - $startTime) / 60;
            //check employee check-in on time
            if ($check_in_time <= $startTime) {
                return [AttendanceStatus::ON_TIME, $diffFromStartTime];
            } else {
                $considerTime = $schedule->consider_time;
                // check if employee come late and have some consider time
                if ($diffFromStartTime > $considerTime) {
                    return [AttendanceStatus::LATE, $diffFromStartTime];
                } else {
                    return [AttendanceStatus::ON_TIME, $diffFromStartTime];
                }
            }
        } else {
            return array();
        }
    }

    public function checkOutStatus($user_id, $check_out_time): array
    {
        /*
         *  LE = Left Early
         *  LT = Left Timely
         *  LL = Left Later
         */

        $user_info = User::find($user_id);
        $schedule = DutySchedule::where('shift_id', $user_info->shift_id)->first();
        if ($schedule) {
            $endTime = strtotime($schedule->end_time);
            $check_out_time = strtotime($check_out_time);
            $diffFromEndTime = ($endTime - $check_out_time) / 60;

            //check employee check-out after end time
            if ($check_out_time > $endTime) {
                return [AttendanceStatus::LEFT_LATER, $diffFromEndTime];
            } //check employee check-out timely
            elseif ($check_out_time == $endTime) {
                return [AttendanceStatus::LEFT_TIMELY, $diffFromEndTime];
            } //check employee check-out before end time
            elseif ($check_out_time < $endTime) {
                return [AttendanceStatus::LEFT_EARLY, $diffFromEndTime];
            } //in general an employee check-out timely
            else {
                return [AttendanceStatus::LEFT_TIMELY, $diffFromEndTime];
            }
        } else {
            return array();
        }
    }

}