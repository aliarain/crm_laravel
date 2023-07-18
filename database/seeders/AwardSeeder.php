<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AwardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // award_types
        $awardType_name = ['Award 1', 'Award 2', 'Award 3'];
        foreach ($awardType_name as $award){
            $awardType = new \App\Models\Award\AwardType();
            $awardType->name = $award;
            $awardType->company_id = 2;
            $awardType->status_id = 1;
            $awardType->created_by = 2;
            $awardType->updated_by = 2;
            $awardType->save();
        }

        // awards
        DB::table('awards')->insert([
            'company_id' => 2,
            'user_id' => 2,
            'award_type_id' => 1,
            'date' => '2020-01-01',
            'gift' => 'Gift 1',
            'amount' => 100,
            'gift_info' => 'Gift info 1',
            'description' => '<strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'status_id' => 1,
            'attachment' => null,
            'created_by' => 2,
            'created_at' => '2020-01-01 00:00:00',
            'updated_at' => '2020-01-01 00:00:00',
        ]);
    }
}
