<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleBillersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_billers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('company_name');
            $table->string('vat_number')->nullable();
            $table->string('email');
            $table->string('phone_number');
            $table->string('address');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->boolean('is_active')->nullable();
            $table->timestamps();
        });


          if (config('app.APP_CRM')){
            // sale_billers demo data
            $data = [
                [
                    'id' => 1,
                    'name' => 'John Doe',
                    'image' => 'John Doe',
                    'company_name' => 'John Doe',
                    'vat_number' => '123456789',
                    'email' => 'john@example.com',
                    'phone_number' => '123456789',
                    'address' => '123 Street',
                    'city' => 'New York',
                    'state' => 'New York',
                    'postal_code' => '12345',
                    'country' => 'United States',
                    'is_active' => 1,
                    'created_at' => '2020-01-01 00:00:00',
                    'updated_at' => '2020-01-01 00:00:00',
                ],
                [
                    'id' => 2,
                    'name' => 'Doe',
                    'image' => null,
                    'company_name' => 'Jane Doe',
                    'vat_number' => '123456789',
                    'email' => 'deo@example.com',
                    'phone_number' => '123456789',
                    'address' => '123 Street',
                    'city' => 'New York',
                    'state' => 'New York',
                    'postal_code' => '12345',
                    'country' => 'United States',
                    'is_active' => 1,
                    'created_at' => '2020-01-01 00:00:00',
                    'updated_at' => '2020-01-01 00:00:00',
                ],

            ];

            DB::table('sale_billers')->insert($data);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_billers');
    }
}
