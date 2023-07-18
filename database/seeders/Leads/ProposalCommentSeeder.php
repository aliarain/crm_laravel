<?php

namespace Database\Seeders\Leads;

use Illuminate\Database\Seeder;

class ProposalCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('proposal_comments')->insert([
            [
                'proposal_id' => 1,
                'staff_id' => 1,
                'content' => 'This is a comment',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 0,
                'is_deleted' => 0,
                'is_active' => 1,
            ],
            [
                'proposal_id' => 1,
                'staff_id' => 1,
                'content' => 'This is another comment',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 0,
                'is_deleted' => 0,
                'is_active' => 1,
            ],
            [
                'proposal_id' => 1,
                'staff_id' => 1,
                'content' => 'This is a third comment',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 0,
                'is_deleted' => 0,
                'is_active' => 1,
            ],
        ]);
    }
}
