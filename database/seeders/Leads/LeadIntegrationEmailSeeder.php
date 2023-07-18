<?php

namespace Database\Seeders\Leads;

use App\Models\Leads\LeadIntegrationEmail;
use Illuminate\Database\Seeder;

class LeadIntegrationEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $list = [
            ['id' => 1,
                'subject' => 'test',
                'body' => 'test',
                'dateadded' => '2021-02-15 12:00:00',
                'leadid' => 1,
                'emailid' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2021-02-15 12:00:00',
                'updated_at' => '2021-02-15 12:00:00'],
            [
                'id' => 2,
                'subject' => 'test',
                'body' => 'test',
                'dateadded' => '2021-02-15 12:00:00',
                'leadid' => 1,
                'emailid' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2021-02-15 12:00:00',
                'updated_at' => '2021-02-15 12:00:00',
            ],
            [
                'id' => 3,
                'subject' => 'test',
                'body' => 'test',
                'dateadded' => '2021-02-15 12:00:00',
                'leadid' => 1,
                'emailid' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2021-02-15 12:00:00',
                'updated_at' => '2021-02-15 12:00:00',
            ],
            [
                'id' => 4,
                'subject' => 'test',
                'body' => 'test',
                'dateadded' => '2021-02-15 12:00:00',
                'leadid' => 1,
                'emailid' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2021-02-15 12:00:00',
                'updated_at' => '2021-02-15 12:00:00',
            ],
            [
                'id' => 1,
                'subject' => 'test',
                'body' => 'test',
                'dateadded' => '2021-02-15 12:00:00',
                'leadid' => 1,
                'emailid' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2021-02-15 12:00:00',
                'updated_at' => '2021-02-15 12:00:00',
            ],
        ];

        foreach ($list as $item) {
            LeadIntegrationEmail::create($item);
        }
    }
}
