<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('sale_id')->nullable();
            $table->double('change')->nullable();
            $table->string('payment_reference');
            $table->double('amount');
            $table->string('paying_method');
            $table->text('payment_note')->nullable();
            $table->integer('purchase_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('account_id')->nullable();
            $table->double('used_points')->nullable();
            $table->integer('cash_register_id')->nullable();
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
        Schema::dropIfExists('sale_payments');
    }
}
