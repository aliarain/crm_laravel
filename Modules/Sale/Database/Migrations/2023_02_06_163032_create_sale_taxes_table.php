<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_taxes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('rate');
            $table->boolean('is_active')->nullable();
            $table->foreignId('created_by')->index('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->index('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        // Sale Taxes Seeding Start
        if (config('app.APP_CRM')){
            $taxes = [10, 15, 20, 25, 50];

            $data = [];
            foreach($taxes as $tax){
                $data[] = [
                    'name' => "vat@".$tax,
                    'rate' => $tax,
                    'is_active' => 1,
                    // 'created_by' => 1,
                    // 'updated_by' => 1,
                ];
            }

            DB::table('sale_taxes')->insert($data);
        }
        // Sale Taxes Seeding End
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_taxes');
    }
}
