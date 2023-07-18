<?php

use Illuminate\Support\Facades\Schema;
use Modules\Sale\Entities\SalePurchase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Sale\Entities\SaleProductPurchase;

class CreateSaleProductPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('sale_product_purchases', function (Blueprint $table) {
                $table->id();
                $table->integer('purchase_id')->nullable();
                $table->integer('product_id')->nullable();
                $table->integer('product_batch_id')->nullable();
                $table->integer('imei_number')->nullable();
                $table->integer('variant_id')->nullable();
                $table->double('qty')->nullable();
                $table->double('recieved')->nullable();
                $table->integer('purchase_unit_id')->nullable();
                $table->double('net_unit_cost')->nullable();
                $table->double('discount')->nullable();
                $table->double('tax_rate')->nullable();
                $table->double('tax')->nullable();
                $table->double('total')->nullable();
                $table->foreignId('created_by')->index('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->index('updated_by')->nullable()->constrained('users');
                $table->timestamps();
            });

              if (config('app.APP_CRM')){
                // sale_purchases
                $purchase = SalePurchase::all();
                foreach ($purchase as $key => $value) {
                    $product_purchase = new SaleProductPurchase();
                    $product_purchase->purchase_id = $value->id;
                    $product_purchase->product_id = 1;
                    $product_purchase->qty = 1;
                    $product_purchase->recieved = 1;
                    $product_purchase->purchase_unit_id = 1;
                    $product_purchase->net_unit_cost = 100;
                    $product_purchase->discount = 0;
                    $product_purchase->tax_rate = 0;
                    $product_purchase->tax = 0;
                    $product_purchase->total = 100;
                    $product_purchase->save();
                }
            }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_product_purchases');
    }
}
