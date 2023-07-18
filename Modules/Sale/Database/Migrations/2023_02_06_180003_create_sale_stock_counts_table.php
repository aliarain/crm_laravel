<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleStockCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_stock_counts', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no');
            $table->integer('warehouse_id');
            $table->string('category_id')->nullable();
            $table->string('brand_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('type')->nullable();
            $table->string('initial_file')->nullable();
            $table->string('final_file')->nullable();
            $table->text('note')->nullable();
            $table->boolean('is_adjusted')->default(false);
            $table->string('action')->nullable();
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
        Schema::dropIfExists('sale_stock_counts');
    }
}
