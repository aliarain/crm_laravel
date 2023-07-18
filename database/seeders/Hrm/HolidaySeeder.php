<?php

namespace Database\Seeders\Hrm;

use App\Models\Company\Company;
use Illuminate\Database\Seeder;
use App\Models\Hrm\Attendance\Holiday;
use Faker\Factory as Faker;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
       
        
        $holidays = [
            '2022-01-01'=>[
                'New Year',  
                'Federal Holiday',
                ''
            ],
            '2022-01-17'=>[
                'Martin Luther King Jr Day',  
                'Federal Holiday',
                '3rd Monday in January'
            ],
            '2022-02-21'=>[
                'Washington\'s Birthday',  
                'Federal Holiday',
                '3rd Monday in February'
            ],
            '2022-05-26'=>[
                'Memorial Day',  
                'Federal Holiday',
                'Last Monday in May'
            ],
            '2022-07-04'=>[
                'Independence Day',  
                'Federal Holiday',
                ''
            ],
            '2022-09-01'=>[
                'Labor Day',  
                'Federal Holiday',
                '1st Monday in September'
            ],
            '2022-10-13'=>[
                'Columbus Day',  
                'Federal Holiday',
                '2nd Monday in October'
            ],
            '2022-11-11'=>[
                'Veterans Day',  
                'Federal Holiday',
                '11th November'
            ],
            '2022-11-24'=>[
                'Thanksgiving Day',  
                'Federal Holiday',
                '4th Thursday in November'
            ],
            '2022-12-25'=>[
                'Christmas Day',  
                'Federal Holiday',
                ''
            ]
        ];

        
        $companies = Company::all();
        foreach ($companies as $company) {
            foreach( $holidays as $date=>$x){
                $holiday= new Holiday();
                $holiday->company_id = $company->id;
                $holiday->title = $x[0];
                $holiday->type = $x[1];
                $holiday->description = $x[2]; 
                $holiday->start_date = $date;
                $holiday->end_date = $date;
                $holiday->status_id = 1;
                $holiday->save();
            }
        }


    }
}
