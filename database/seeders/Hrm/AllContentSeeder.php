<?php

namespace Database\Seeders\Hrm;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = 'database/seeders/sqls/all_contents.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
