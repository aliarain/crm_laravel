<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockSaleHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_sale_histories', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            
            $table->foreignId('stock_sale_id')->constrained('stock_sales')->cascadeOnDelete();
            $table->foreignId('stock_product_id')->constrained('stock_products')->cascadeOnDelete();
            $table->bigInteger('quantity');
            $table->double('price',16,2);
            $table->double('discount',16,2);
            $table->double('tax',16,2);
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
        Schema::dropIfExists('stock_sale_histories');
    }
}
