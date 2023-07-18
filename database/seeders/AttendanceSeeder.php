<?php

namespace Database\Seeders;

use App\Models\Hrm\Shift\Shift;
use App\Models\User;
use App\Models\Company\Company;
use Illuminate\Database\Seeder;
use App\Models\Hrm\Attendance\Holiday;
use App\Models\Hrm\Attendance\Attendance;
use App\Models\Hrm\Department\Department;
use App\Models\Hrm\Attendance\DutySchedule;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $holidays = [
            ['21 February - Language Day', '2022-02-21', '2022-02-21'],
            ['1st May - Labour Day', '2022-05-01', '2022-05-01'],
            ['9th May - Independence Day', '2022-05-09', '2022-05-09'],
            ['15th August - Nation Day', '2022-08-15', '2022-08-15'],
            ['25th December - Christmas Day', '2022-12-25', '2022-12-25'],
            ['26th December - Boxing Day', '2022-12-26', '2022-12-26']
        ];

        $companies = Company::all();
        foreach ($companies as $company) {
            foreach ($holidays as $holiday) {
                $s = new Holiday();
                $s->company_id = $company->id;
                $s->title = $holiday[0];
                $s->description = $holiday[0];
                $s->start_date = $holiday[1];
                $s->end_date = $holiday[2];
                $s->status_id =    1;
                $s->save();
            }
        }



        foreach ($companies as $company) {
            $deparments = Shift::where('company_id', $company->id)->get();
            foreach ($deparments as $department) {
                $s = new DutySchedule();
                $s->company_id = $company->id;
                $s->shift_id = $department->id;
                $s->start_time = '09:00:00';
                $s->end_time = '18:00:00';
                $s->hour = 8;
                $s->consider_time = 30;
                $s->status_id =    1;
                $s->save();
            }
        }


        $users = User::all();
        foreach ($users as $user) {
            // month loop
            for ($i = 1; $i <= 3; $i++) {
                // day loop
                for ($j = 1; $j <= 31; $j++) {
                    $s = new Attendance();
                    $s->user_id = $user->id;
                    $s->company_id = $user->company_id;
                    $s->date = '2022-' . $i . '-' . $j;
                    $s->check_in = '2022-' . $i . '-' . $j . ' 09:00:00';
                    $randomHourAdd = 7 + rand(0, 2);
                    $s->check_out = '2022-' . $i . '-' . $j . ' ' . ($randomHourAdd + 9) . ':00:00';
                    $s->status_id =    1;
                    $s->late_reason = 'N/A';
                    $s->save();
                }
            }
        }
    }
}
