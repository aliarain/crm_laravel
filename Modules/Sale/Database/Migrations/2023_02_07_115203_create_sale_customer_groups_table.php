<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleCustomerGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_customer_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('percentage');
            $table->boolean('is_active')->nullable();
            $table->timestamps();
        });

          if (config('app.APP_CRM')){
            // sale_customer_groups demo data
            $data = [
                [
                    'id' => 1,
                    'name' => 'General',
                    'percentage' => '0',
                    'is_active' => 1,
                    'created_at' => '2020-01-01 00:00:00',
                    'updated_at' => '2020-01-01 00:00:00',
                ],
                [
                    'id' => 2,
                    'name' => 'Wholesale',
                    'percentage' => '10',
                    'is_active' => 1,
                    'created_at' => '2020-01-01 00:00:00',
                    'updated_at' => '2020-01-01 00:00:00',
                ],
                [
                    'id' => 3,
                    'name' => 'Retail',
                    'percentage' => '5',
                    'is_active' => 1,
                    'created_at' => '2020-01-01 00:00:00',
                    'updated_at' => '2020-01-01 00:00:00',
                ],
            ]; 

            DB::table('sale_customer_groups')->insert($data);


        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_customer_groups');
    }
}
