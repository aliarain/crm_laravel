<?php

namespace Database\Seeders\Leads;

use Illuminate\Database\Seeder;

class LeadSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subscriptions = [
            [
                'name' => 'Standard',
                'description' => 'Standard subscription',
                'clientid' => 1,
                'date' => '2022-01-01',
                'currency' => 1,
                'tax_id' => 1,
                'stripe_subscription_id' => 'sub_00000000000000',
                'quantity' => 1,
                'hash' => 'hash1',
                'created_from' => 1,
                'date_subscribed' => '2022-01-01 12:00:00',
            ],
            [
                'name' => 'Premium',
                'description' => 'Premium subscription',
                'clientid' => 2,
                'date' => '2022-02-01',
                'currency' => 1,
                'tax_id' => 2,
                'stripe_subscription_id' => 'sub_11111111111111',
                'quantity' => 2,
                'hash' => 'hash2',
                'created_from' => 1,
                'date_subscribed' => '2022-02-01 12:00:00',
            ],
        ];

        DB::table('lead_subscriptions')->insert($subscriptions);

    }
}
