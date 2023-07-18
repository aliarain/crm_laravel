<?php

namespace Database\Seeders\Hrm;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use App\Models\Hrm\Appoinment\Appoinment;
use App\Models\Hrm\Appoinment\AppoinmentParticipant;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $users=User::where('company_id',2)->get();
        $user_ids=$users->pluck('id')->toArray();
        foreach ($users as $key => $user) {
            $next_hour=date('h')+1;
            $appointment=new Appoinment();
            $appointment->company_id=$user->company_id;
            $appointment->created_by=$user->id;
            $appointment->appoinment_with=$user_ids[array_rand($user_ids, 1)];
            $appointment->date=date('Y-m-') . rand(1,28);
            $appointment->appoinment_start_at	 = date('h:i:s');
            $appointment->appoinment_end_at	 = $next_hour.":".date('i:s');
            $appointment->title = $faker->sentence;
            $appointment->description = $faker->text;
            $appointment->location = $faker->address;
            $appointment->save();
        }
        $appointments=Appoinment::get();
        foreach ($appointments as $key => $appointment) {
            $participant=new AppoinmentParticipant();
            $participant->appoinment_id=$appointment->id;
            $participant->participant_id=$users->first()->id;
            $participant->is_agree=1;
            $participant->save();

            $participant=new AppoinmentParticipant();
            $participant->appoinment_id=$appointment->id;
            $participant->participant_id=$appointment->created_by;
            $participant->is_agree=1;
            $participant->save();
        }
    }
}
