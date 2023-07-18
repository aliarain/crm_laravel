<?php

namespace Database\Seeders\Hrm;

use App\Models\Hrm\Attendance\Weekend;
use Illuminate\Database\Seeder;

class WeekendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $days=['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'] ;

        foreach ($days as $key => $day) {
            Weekend::create([
                'name' => $day,
                'is_weekend' => 'no',
                'order' => $key+1,
                'status_id' => 1,
                'company_id' => 2,
            ]);
        }
    }
}
