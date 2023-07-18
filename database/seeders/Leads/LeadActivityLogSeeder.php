<?php

namespace Database\Seeders\Leads;

use Illuminate\Database\Seeder;
use App\Models\Leads\LeadActivityLog;

class LeadActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a few sample activity logs
        for ($i = 1; $i <= 10; $i++) {
            LeadActivityLog::create([
                'lead_id' => $i,
                'description' => 'Sent follow-up email',
                'additional_data' => json_encode([
                    'subject' => 'Follow-up for Proposal #123',
                    'recipient' => 'john@example.com',
                ]),
                'date' => now(),
                'staff_id' => 1,
                'full_name' => 'John Doe',
                'created_by' => 1,
                'updated_by' => 1,
            ]);
        }
    }
}
