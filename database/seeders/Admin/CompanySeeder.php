<?php

namespace Database\Seeders\Admin;

use App\Models\Company\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Company::create([
            'country_id'        => 16, // Bangladesh
            'name'              => 'Super Admin',
            'company_name'      => 'Company 01',
            'email'             => 'superadmin@onesttech.com',
            'phone'             => '+8801910077628',
            'total_employee'    => 10,
            'business_type'     => 'Service',
            'is_main_company'   => 'yes',
        ]);

        // company user
        Company::create([
            'country_id'        => 223, // United States
            'name'              => 'Admin',
            'company_name'      => 'Company 02',
            'email'             => 'admin@onesttech.com',
            'phone'             => '+880177777777',
            'total_employee'    => 400,
            'business_type'     => 'Service',
            'is_main_company'   => 'no',
        ]);
 

    }
}
