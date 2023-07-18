<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable(); 
            $table->string('email')->nullable(); 
            $table->text('address');
            $table->boolean('is_active')->nullable();
            $table->foreignId('created_by')->index('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->index('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        if (config('app.APP_CRM')){
            // warehouse seed 
            $warehouses = [
                [
                    'name' => 'Warehouse 1',
                    'phone' => '01700000000',
                    'email' => 'warehouse1@example.com',
                    'address' => 'Warehouse 1 Address',
                    'is_active' => true,
                ],
                [
                    'name' => 'Warehouse 2',
                    'phone' => '01700000000',
                    'email' => 'warehouse2@example.com',
                    'address' => 'Warehouse 2 Address',
                    'is_active' => true,
                ],
            ];

            DB::table('sale_warehouses')->insert($warehouses);

        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_warehouses');
    }
}
