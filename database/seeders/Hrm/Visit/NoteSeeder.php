<?php

namespace Database\Seeders\Hrm\Visit;

use App\Models\Visit\Visit;
use Faker\Factory as Faker;
use App\Models\Visit\VisitNote;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
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
            $visit=VisitNote::create([
                'visit_id'=>$visit->id,
                'note'=>$faker->text,
            ]);
        }
    }
}
