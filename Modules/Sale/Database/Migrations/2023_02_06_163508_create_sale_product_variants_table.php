<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_product_variants', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('variant_id');
            $table->integer('position');
            $table->string('item_code');
            $table->double('additional_price')->nullable();
            $table->double('qty');
            $table->double('additional_cost');
            $table->foreignId('created_by')->index('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->index('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_product_variants');
    }
}
