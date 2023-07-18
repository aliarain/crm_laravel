<?php

namespace Database\Seeders\Hrm;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_types')->insert([
            [
                'name' => 'Cash',
                'type' => 1,
                'status_id' => 1,
            ],
            [
                'name' => 'Credit Card',
                'type' => 2,
                'status_id' => 1,
            ],
            [
                'name' => 'Debit Card',
                'type' => 3,
                'status_id' => 1,
            ],
            [
                'name' => 'Bank',
                'type' => 4,
                'status_id' => 1,
            ],
        ]);
    }
}
