<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('code')->nullable();
            $table->string('type')->nullable();
            $table->string('barcode_symbology')->nullable();
            $table->integer('brand_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('unit_id')->nullable();
            $table->integer('purchase_unit_id')->nullable();
            $table->integer('sale_unit_id')->nullable();
            $table->double('cost')->nullable();
            $table->double('price')->nullable();
            $table->integer('daily_sale_objective')->nullable();
            $table->boolean('is_variant')->nullable()->comment('0 = No, 1 = Yes');

            $table->string('product_list')->nullable();
            $table->string('qty_list')->nullable();
            $table->string('price_list')->nullable();
            $table->string('file')->nullable();
            $table->boolean('is_imei')->default(0)->comment('0 = No, 1 = Yes');
            $table->boolean('is_batch')->default(0)->comment('0 = No, 1 = Yes');
            $table->boolean('is_embeded')->default(0)->comment('0 = No, 1 = Yes');
            $table->string('variant_list')->nullable();
            $table->text('variant_option')->nullable();
            $table->text('variant_value')->nullable();
            $table->boolean('is_diffPrice')->default(0)->comment('0 = No, 1 = Yes');

            $table->double('alert_quantity')->nullable();
            $table->integer('tax_id')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('tax_method')->nullable();
            $table->tinyInteger('featured')->nullable();
            $table->tinyInteger('embedded_barcode')->nullable();
            $table->longText('image')->nullable();
            $table->text('product_details')->nullable();
            $table->tinyInteger('has_variant')->default(0)->comment('0 = No, 1 = Yes');
            $table->tinyInteger('has_different_price')->default(0)->comment('0 = No, 1 = Yes');
            $table->tinyInteger('has_badge')->default(0)->comment('0 = No, 1 = Yes');
            $table->date('expired_date')->nullable();
            $table->string('imei_serial')->nullable();
            $table->tinyInteger('promotion')->default(0)->comment('0 = No, 1 = Yes');
            $table->double('promotion_price')->nullable();
            $table->date('starting_date')->nullable();
            $table->date('last_date')->nullable();
            $table->boolean('is_active')->nullable();
            $table->foreignId('created_by')->index('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->index('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });


          if (config('app.APP_CRM')){

            for($i = 0; $i < 10; $i++) {
                
                // insert data 

                $data = [
                    'name' => 'Product ' . $i,
                    'slug' => 'product-' . $i,
                    'code' => rand(100000, 999999),
                    'type' => 'standard',
                    'barcode_symbology' => 'C128',
                    'brand_id' => 1,
                    'category_id' => 1,
                    'unit_id' => 1,
                    'purchase_unit_id' => 1,
                    'sale_unit_id' => 1,
                    'cost' => 100,
                    'price' => 100,
                    'daily_sale_objective' => 100,
                    'is_variant' => 0,
                    'product_list' => null,
                    'qty_list' => null,
                    'price_list' => null,
                    'file' => null,
                    'is_imei' => 0,
                    'is_batch' => 0,
                    'is_embeded' => 0,
                    'variant_list' => null,
                    'variant_option' => null,
                    'variant_value' => null,
                    'is_diffPrice' => 0,
                    'alert_quantity' => 100,
                    'tax_id' => 1,
                    'qty' => 100,
                    'tax_method' => 1,
                    'featured' => 0,
                    'embedded_barcode' => 0,
                    'image' => null,
                    'product_details' => null,
                    'has_variant' => 0,
                    'has_different_price' => 0,
                    'has_badge' => 0,
                    'expired_date' => null,
                    'imei_serial' => null,
                    'promotion' => 0,
                    'promotion_price' => null,
                    'starting_date' => null,
                    'last_date' => null,
                    'is_active' => 1, 
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                DB::table('sale_products')->insert($data);
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
        Schema::dropIfExists('sale_products');
    }
}
