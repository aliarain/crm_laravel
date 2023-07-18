<?php

namespace Database\Seeders\Leads;

use Illuminate\Database\Seeder;

class ProposalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('proposals')->insert([
            [
                'subject' => 'Proposal 1',
                'content' => 'Proposal content goes here.',
                'addedfrom' => 1,
                'datecreated' => now(),
                'subtotal' => 100.00,
                'total_tax' => 0.00,
                'discount_percent' => 0.00,
                'discount_total' => 0.00,
                'show_quantity_as' => 1,
                'currency' => 1,
                'date' => now(),
                'hash' => 'abc123',
                'status' => 1,
            ],
            [
                'subject' => 'Proposal 2',
                'content' => 'Proposal content goes here.',
                'addedfrom' => 1,
                'datecreated' => now(),
                'subtotal' => 100.00,
                'total_tax' => 0.00,
                'discount_percent' => 0.00,
                'discount_total' => 0.00,
                'show_quantity_as' => 1,
                'currency' => 1,
                'date' => now(),
                'hash' => 'abc123',
                'status' => 1,
            ],

        ]);

    }
}
