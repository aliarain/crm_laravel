<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DatabaseUpdateForV33 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         // Database Update for v3.3
         Schema::table('duty_schedules', function ($table) {
            if (!Schema::hasColumn('duty_schedules', 'end_on_same_date')) {
                $table->boolean('end_on_same_date')->default(1);
            }
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
