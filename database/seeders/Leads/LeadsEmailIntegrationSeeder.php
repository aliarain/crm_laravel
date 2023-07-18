<?php

namespace Database\Seeders\Leads;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadsEmailIntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('leads_email_integration')->insert([
            'id' => 1,
            'active' => 0,
            'email' => 'example@example.com',
            'imap_server' => 'imap.example.com',
            'password' => 'password123',
            'check_every' => 5,
            'responsible' => 1,
            'lead_source' => 1,
            'lead_status' => 1,
            'encryption' => null,
            'folder' => 'INBOX',
            'last_run' => null,
            'notify_lead_imported' => true,
            'notify_lead_contact_more_times' => true,
            'notify_type' => null,
            'notify_ids' => null,
            'mark_public' => 0,
            'only_loop_on_unseen_emails' => true,
            'delete_after_import' => 0,
            'create_task_if_customer' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
