<?php

namespace Database\Seeders\Leads;

use App\Models\Company\Company;
use App\Models\Leads\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        generateLeadSeeder();

    }
}
