<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DatabaseUpdatesForV31 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            // Database Update for v3.1
            Schema::table('users', function ($table) {
                if (!Schema::hasColumn('users', 'contract_start_date')) {
                    $table->date('contract_start_date')->nullable();
                }
                if (!Schema::hasColumn('users', 'contract_end_date')) {
                    $table->date('contract_end_date')->nullable();
                }
                if (!Schema::hasColumn('users', 'payslip_type')) {
                    $table->tinyInteger('payslip_type')->default(1)->comment('1 = monthly, 2 = weekly, 3 = daily');
                }
                if (!Schema::hasColumn('users', 'late_check_in')) {
                    $table->integer('late_check_in')->default(0);
                }
                if (!Schema::hasColumn('users', 'early_check_out')) {
                    $table->integer('early_check_out')->default(0);
                }
                if (!Schema::hasColumn('users', 'extra_leave')) {
                    $table->integer('extra_leave')->default(0);
                }
                if (!Schema::hasColumn('users', 'monthly_leave')) {
                    $table->integer('monthly_leave')->default(0);
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
