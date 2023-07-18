<?php

namespace Database\Seeders\Hrm\Shift;

use App\Models\Company\Company;
use App\Models\Hrm\Shift\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shifts = ['Day', 'Night'];
        $companies = Company::all();
        foreach ($companies as $company) {
            foreach ($shifts as $shift) {
                $s = new Shift();
                $s->name = $shift;
                $s->company_id = $company->id;
                $s->status_id = 1;
                $s->save();
            }
        }
    }
}
