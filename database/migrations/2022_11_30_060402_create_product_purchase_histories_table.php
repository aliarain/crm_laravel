<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPurchaseHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_purchase_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();

            $table->foreignId('product_purchase_id')->constrained('product_purchases')->cascadeOnDelete();
            $table->string('batch_no', 191);
            
            $table->foreignId('product_unit_id')->constrained('product_units')->cascadeOnDelete();
            $table->bigInteger('quantity');
            $table->double('price',16,2);
            $table->double('discount',16,2);
            $table->double('total',16,2);
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
        Schema::dropIfExists('product_purchase_histories');
    }
}
