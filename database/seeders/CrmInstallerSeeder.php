<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CrmInstallerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            ini_set('max_execution_time', -1);
            $sql = file_get_contents('public/installer/db/crm.sql');
            DB::unprepared($sql);
        } catch (\Throwable$th) {
            \Log::info($th);
        }

    }
}
