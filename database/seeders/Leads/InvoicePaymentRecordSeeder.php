<?php

namespace Database\Seeders\Leads;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InvoicePaymentRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = [
            [
                'invoiceid' => 1,
                'amount' => 500.00,
                'paymentmode' => 'credit card',
                'paymentmethod' => 'Stripe',
                'date' => Carbon::now()->toDateString(),
                'daterecorded' => Carbon::now(),
                'note' => 'Payment received for Invoice 1',
                'transactionid' => 'ch_1FzJb8KbZMSgxy6ZkY4cGp6F',
            ],
            [
                'invoiceid' => 2,
                'amount' => 250.00,
                'paymentmode' => 'bank transfer',
                'paymentmethod' => 'HSBC',
                'date' => Carbon::now()->toDateString(),
                'daterecorded' => Carbon::now(),
                'note' => 'Payment received for Invoice 2',
                'transactionid' => null,
            ],
            [
                'invoiceid' => 3,
                'amount' => 1000.00,
                'paymentmode' => 'credit card',
                'paymentmethod' => 'Stripe',
                'date' => Carbon::now()->toDateString(),
                'daterecorded' => Carbon::now(),
                'note' => 'Payment received for Invoice 3',
                'transactionid' => 'ch_1FzJb8KbZMSgxy6ZkY4cGp6F',
            ],
            // Add more records as needed
        ];

        DB::table('invoice_payment_records')->insert($records);
    }
}
