<?php

namespace Database\Seeders\Hrm;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // sample data
        DB::table('subscription_plans')->insert([
            'title' => 'Basic',
            'identifier' => 'basic',
            'stripe_id' => 'plan_HeC7XMT2SVe21K',
            'status_id' => 1
        ]);
        DB::table('subscription_plans')->insert([
            'title' => 'Pro',
            'identifier' => 'pro',
            'stripe_id' => 'plan_HeC7XMT2SVe21L',
            'status_id' => 1
        ]);

    }
}
