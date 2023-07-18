<?php

namespace Database\Seeders\Hrm\Visit;

use App\Models\Visit\Visit;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\Visit\VisitSchedule;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $visits=Visit::get();
        foreach ($visits as $key => $visit) {
            $visitSchedule=new VisitSchedule;
            $visitSchedule->visit_id=$visit->id;
            $visitSchedule->status=$faker->randomElement(['end','reached','started', 'created']);
            switch ($visitSchedule->status) {
                case 'end':
                    $log_title='End Visit';
                    break;
                case 'reached':
                    $log_title='Reached Destination';
                    break;
                case 'started':
                    $log_title='Started Visit';
                    break;
                case 'cancelled':
                    $log_title='Cancelled Visit';
                    break;
                
                default:
                    $log_title='Visit Rescheduled';
                    break;
            }
            $visitSchedule->title=$log_title;
            $visitSchedule->save();
        }
    }
}
