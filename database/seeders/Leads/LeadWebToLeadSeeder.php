<?php

namespace Database\Seeders\Leads;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadWebToLeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('web_to_lead')->insert([
            'form_key' => 'example_form',
            'lead_source' => 1,
            'lead_status' => 2,
            'notify_lead_imported' => 1,
            'notify_type' => 'email',
            'notify_ids' => 'example@example.com',
            'responsible' => 0,
            'name' => 'Example Form',
            'form_data' => '{"field1":"value1","field2":"value2"}',
            'recaptcha' => 0,
            'submit_btn_name' => 'Submit',
            'submit_btn_text_color' => '#ffffff',
            'submit_btn_bg_color' => '#84c529',
            'success_submit_msg' => 'Form submitted successfully!',
            'submit_action' => 0,
            'lead_name_prefix' => null,
            'submit_redirect_url' => null,
            'language' => null,
            'allow_duplicate' => 1,
            'mark_public' => 0,
            'track_duplicate_field' => null,
            'track_duplicate_field_and' => null,
            'create_task_on_duplicate' => 0,
            'dateadded' => now()
        ]);
    }
}
