<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_no');
            $table->string('name');
            $table->double('initial_balance')->nullable();
            $table->double('total_balance');
            $table->text('note')->nullable();
            $table->boolean('is_default');
            $table->boolean('is_active');
            $table->timestamps();
        });

          if (config('app.APP_CRM')){
            DB::table('sale_accounts')->insert([
                [
                    'account_no' => '1000',
                    'name' => 'Rayhan',
                    'initial_balance' => 500,
                    'total_balance' => 1000,
                    'note' => 'Rayhan',
                    'is_default' => 1,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'account_no' => '2000',
                    'name' => 'Mr X',
                    'initial_balance' => 500,
                    'total_balance' => 1000,
                    'note' => 'Mr X has been died before 1 year',
                    'is_default' => 0,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'account_no' => '3000',
                    'name' => 'Mr X',
                    'initial_balance' => 500,
                    'total_balance' => 1000,
                    'note' => 'Mr X has been died',
                    'is_default' => 0,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_accounts');
    }
}
