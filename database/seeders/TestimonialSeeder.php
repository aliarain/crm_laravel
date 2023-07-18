<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Testimonial = new Testimonial();
        $Testimonial->company_id =1;
        $Testimonial->message =' Never felt this much relaxed in last couple of years It’s quiet comprehensible and helped me manage things very easily. A great software indeed!';
        $Testimonial->save();

        
    }
}
