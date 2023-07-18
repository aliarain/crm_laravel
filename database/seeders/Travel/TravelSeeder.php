<?php

namespace Database\Seeders\Travel;

use App\Models\Travel\Travel;
use Illuminate\Database\Seeder;
use App\Models\Travel\TravelType;

class TravelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // travel type
        $names = [
            'Business',
            'Personal',
            'Vacation',
            'Other'
        ];

        foreach ($names as $name) {
            $travelType = new TravelType();
            $travelType->name = $name;
            $travelType->company_id = 2;
            $travelType->status_id = 2;
            $travelType->created_by = 2;
            $travelType->save();
        }

        // travel
        $travel = new Travel();
        $travel->company_id = 2;
        $travel->user_id = 2;
        $travel->created_by = 2;
        $travel->travel_type_id = 1;
        $travel->start_date = date('Y-m-d');
        $travel->end_date = date('Y-m-d', strtotime('+7 day'));
        $travel->status_id = 1;
        $travel->expect_amount = 100;
        $travel->amount = 100;
        $travel->description = '<strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.';
        $travel->purpose = 'test';
        $travel->place = 'test';
        $travel->mode = 'bus';
        $travel->save();
    }
}
