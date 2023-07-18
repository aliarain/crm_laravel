<?php

namespace Database\Seeders\Hrm\Visit;

use App\Models\User;
use App\Models\Visit\Visit;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class VisitSeeder extends Seeder
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
        foreach ($users as $key => $user) {
            for($i=1; $i<=6; $i++){
                $visit = new Visit();
                $visit->date =  $faker->date($format = 'Y-m-d', $max = 'now');
                $visit->title	 = $faker->sentence;
                $visit->description = $faker->text;
                $visit->user_id = $user->id;
                $visit->status = $faker->randomElement(['completed','cancelled','reached','started', 'created']);
                $visit->company_id=$user->company_id;
                $visit->save();
            }
        }
    }
}
