<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_customers', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_group_id');
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number');
            $table->string('address');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->string('tax_no')->nullable();
            $table->double('points')->nullable();
            $table->integer('user_id')->nullable();
            $table->double('deposit')->nullable();
            $table->double('expense')->nullable();
            $table->boolean('is_active')->nullable();
            $table->timestamps();
        });

          if (config('app.APP_CRM')){
            // sale_customers demo data
            $data = [
                [
                    'id' => 1,
                    'customer_group_id' => 1,
                    'name' => 'John Doe',
                    'company_name' => 'John Doe',
                    'email' => 'customer@gmail.com',
                    'phone_number' => '123456789',
                    'address' => '123 Street',
                    'city' => 'New York',
                    'state' => 'New York',
                    'postal_code' => '12345',
                    'country' => 'United States',
                    'tax_no' => '123456789',
                    'points' => 0,
                    'user_id' => 1,
                    'deposit' => 0,
                    'expense' => 0,
                    'is_active' => 1,
                    'created_at' => '2020-01-01 00:00:00',
                    'updated_at' => '2020-01-01 00:00:00',
                ], 
            ];
            // data insert 
            DB::table('sale_customers')->insert($data);

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_customers');
    }
}
