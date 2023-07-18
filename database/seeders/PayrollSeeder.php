<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // insert commission to payroll
        DB::table('commissions')->insert([
            [
                'name' => 'Bonus',
                'type' => 1,
                'company_id' => 2,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now()

            ],
            [

                'name' => 'Penalty',
                'type' => 2,
                'company_id' => 2,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now()

            ]

        ]);

        // insert advanced type to payroll
        DB::table('advance_types')->insert([
            [
                'name' => 'Salary Advance',
                'company_id' => 2,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now()

            ],
            [

                'name' => 'Loan',
                'company_id' => 2,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now()

            ]

        ]);

        // insert advance salary to payroll


        // insert Account
        DB::table('accounts')->insert([
            [
                'company_id' => 2,
                'name' => 'Account 1',
                'ac_name' => 'John Doe',
                'ac_number' => '123456789',
                'code' => '123456789',
                'branch' => 'California',
                'amount' => 160000,
                'status_id' => 1,
                'created_by' => 2,
                'updated_by' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]); 

    }
}
