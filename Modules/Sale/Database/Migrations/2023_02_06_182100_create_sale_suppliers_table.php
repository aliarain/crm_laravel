<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('company_name')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->boolean('is_active')->nullable();
            $table->foreignId('created_by')->index('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->index('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });

          if (config('app.APP_CRM')){
            // supplier seed 
            $suppliers = [
                [
                    'name' => 'Supplier 1',
                    'company_name' => 'Supplier 1',
                    'email' => 'supplier1@gmail.com',
                    'phone_number' => '01700000000',
                    'address' => 'Supplier 1 Address',
                    'city' => 'Dhaka',
                    'state' => 'Dhaka',
                    'postal_code' => '1200',
                    'country' => 'Bangladesh',
                    'is_active' => true,
                ],
                [
                    'name' => 'Supplier 2',
                    'company_name' => 'Supplier 2',
                    'email' => 'supplier2@gmail.com',
                    'phone_number' => '01700000000',
                    'address' => 'Supplier 2 Address',
                    'city' => 'Dhaka',
                    'state' => 'Dhaka',
                    'postal_code' => '1200',
                    'country' => 'Bangladesh',
                    'is_active' => true,
                ]
            ];

            DB::table('sale_suppliers')->insert($suppliers);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_suppliers');
    }
}
