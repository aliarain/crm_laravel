<?php

namespace Database\Seeders\Leads;

use App\Models\Company\Company;
use Illuminate\Database\Seeder;
use App\Models\Leads\LeadSource;

class LeadSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $lead_sources = [
            'Advertisement',
            'Cold Call',
            'Employee Referral',
            'External Referral',
            'Online Store',
            'Partner',
            'Public Relations',
            'Sales Mail Alias',
            'Seminar Partner',
            'Internal Seminar',
            'Trade Show',
            'Web Research',
            'Chat',
            'Email',
            'Support Portal',
            'Phone',
            'Social Media',
            'Other',
        ];

        $companies = Company::all();
        foreach ($companies as $company) {
            foreach ($lead_sources as $lead_source) {
                LeadSource::create([
                    'title' => $lead_source,
                    'company_id' => $company->id,
                    'status_id' => 1,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);
            }
        }

    }
}
