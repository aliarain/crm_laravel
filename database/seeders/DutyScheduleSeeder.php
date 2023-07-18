<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hrm\Attendance\DutySchedule;

class DutyScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //new duty schedule
        $duty_schedule = new DutySchedule;
        $duty_schedule->company_id = 2;
        $duty_schedule->shift_id = 4;
        $duty_schedule->start_time = '09:00:00';
        $duty_schedule->end_time = '17:00:00';
        $duty_schedule->consider_time = '15';
        $duty_schedule->hour = '8';
        $duty_schedule->status_id = 1;
        $duty_schedule->save();
    }
}
