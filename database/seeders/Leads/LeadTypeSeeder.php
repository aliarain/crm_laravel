<?php

namespace Database\Seeders\Leads;

use App\Models\LeadType;
use App\Models\Company\Company;
use Illuminate\Database\Seeder;

class LeadTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lead_types = [
            'Institution/Software 01',
            'Institution/Software 02',
            'Institution/Software 03',
            'Institution/Software 04',
            'Institution/Software 05' 
        ];

        $companies = Company::all();
        foreach ($companies as $company) {
            foreach ($lead_types as $lead_type) {
                LeadType::create([
                    'title' => $lead_type,
                    'company_id' => $company->id,
                    'status_id' => 1,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);
            }
        }
    }
}
