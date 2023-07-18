<?php

namespace Database\Seeders;

use App\Models\Company\Company;
use Illuminate\Database\Seeder;
use App\Models\Hrm\Attendance\Weekend;

class WeekendSetupSeeder extends Seeder
{
  
    public function run()
    {
        $weekdays = [
            'saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday',
        ];

        $companies = Company::all();
        foreach ($companies as $company) {
            foreach ($weekdays as $weekday) {
            $isWeekend = 'no';
            if ( $weekday == 'saturday' || $weekday == 'sunday') {
                $isWeekend = 'yes';
            }
                Weekend::create([
                    'company_id' => $company->id,
                    'name' => $weekday,
                    'status_id' => 1,
                    'is_weekend' => $isWeekend,
                ]);
            }
        } 
    }
}
