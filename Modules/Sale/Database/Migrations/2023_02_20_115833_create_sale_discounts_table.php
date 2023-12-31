<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('applicable_for');
            $table->longText('product_list')->nullable();
            $table->date('valid_from');
            $table->date('valid_till');
            $table->string('type');
            $table->double('value');
            $table->double('minimum_qty');
            $table->double('maximum_qty');
            $table->string('days');
            $table->boolean('is_active');
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
        Schema::dropIfExists('sale_discounts');
    }
}
