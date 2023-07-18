<?php

namespace Database\Seeders\Hrm;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\Hrm\Meeting\Meeting;

class MeetingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $users=User::get();
        $user_ids=$users->pluck('id')->toArray();
        foreach ($users as $key => $user) {
            $meeting=new Meeting();
            $meeting->company_id=$user->company_id;
            $meeting->user_id=$user->id;
            $meeting->date=date('Y-m-') . rand(1,28).' '.rand(0,12).':'.rand(0,59).':'.rand(0,59);
            $meeting->title = $faker->sentence;
            $meeting->description = $faker->text;
            $meeting->save();
        }
    }
}
