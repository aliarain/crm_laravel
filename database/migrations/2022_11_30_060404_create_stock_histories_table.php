<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();

            $table->foreignId('stock_product_id')->constrained('stock_products')->cascadeOnDelete();
            $table->foreignId('product_purchase_id')->constrained('product_purchases')->cascadeOnDelete();
            $table->string('invoice_no', 191);

            $table->string('batch_no', 191); //131

            $table->date('expiry_date')->nullable();
            $table->bigInteger('quantity');
            $table->foreignId('product_unit_id')->constrained('product_units')->cascadeOnDelete();
            $table->double('purchase_price', 16, 2);
            $table->double('selling_price', 16, 2);
            $table->double('total', 16, 2);

            $table->string('discount_type')->comment('percentage or fixed')->nullable();
            $table->double('discount', 16, 2)->nullable();

            $table->double('grand_total', 16, 2)->nullable();

            $table->foreignId('status_id')->index('status_id')->default(1)->constrained('statuses');

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
        Schema::dropIfExists('stock_histories');
    }
}
