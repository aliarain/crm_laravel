<?php

namespace Database\Seeders\Leads;

use App\Models\Company\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = Company::all();
        foreach ($companies as $company) {
 

            DB::table('lead_statuses')->insert([
                [
                    'title'         => 'New',
                    'order'         => 1, 
                    'border_color'         => '#007bff',
                    'background_color'      => '#007bff',
                    'text_color'    => '#fff',
                    
                    'status_id'     => 1,
                    'created_by'    => 1,
                    'updated_by'    => 1,
                    'company_id'    => $company->id
                ],
                [
                    'title'         => 'Contacted',
                    'order'         => 2,
                    'border_color'         => '#6c757d',
                    'background_color'      => '#6c757d',
                    'text_color'    => '#fff',
                    
                    'status_id'     => 1,
                    'created_by'    => 1,
                    'updated_by'    => 1,
                    'company_id'    => $company->id
                ],
                [
                    'title'         => 'Qualified',
                    'order'         => 3,
                    'border_color'         => '#17a2b8',
                    'background_color'      => '#17a2b8',
                    'text_color'    => '#fff',
                    
                    'status_id'     => 1,
                    'created_by'    => 1,
                    'updated_by'    => 1,
                    'company_id'    => $company->id,
                ],
                [
                    'title'         => 'Unqualified',
                    'order'         => 4,
                    'border_color'         => '#ffc107',
                    'background_color'      => '#ffc107',
                    'text_color'    => '#fff',
                    
                    'status_id'     => 1,
                    'created_by'    => 1,
                    'updated_by'    => 1,
                    'company_id'    => $company->id,
                ],
                [
                    'title'         => 'Proposal Sent',
                    'order'         => 5,
                    'border_color'         => '#28a745',
                    'background_color'      => '#28a745',
                    'text_color'    => '#fff',
                    
                    'status_id'     => 1,
                    'created_by'    => 1,
                    'updated_by'    => 1,
                    'company_id'    => $company->id
                ],
                [
                    'title'         => 'Negotiation/Review',
                    'order'         => 6,
                    'border_color'         => '#777777',
                    'background_color'      => '#ffffff',
                    'text_color'    => '#777777',
                    
                    'status_id'     => 1,
                    'created_by'    => 1,
                    'updated_by'    => 1,
                    'company_id'    => $company->id
                ],
                [
                    'title' => 'Lost',
                    'order' => 7,
                    'border_color' => '#dc3545',
                    'background_color' => '#dc3545',
                    'text_color' => '#fff',
                    
                    'status_id' => 1,
                    'created_by' => 1,
                    'updated_by' => 1,
                    'company_id' => $company->id

                ]
            ]);
        }
    }
}
