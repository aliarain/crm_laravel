<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no');
            $table->integer('status')->nullable();
            $table->integer('from_warehouse_id')->nullable();
            $table->integer('to_warehouse_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('item')->nullable();
            $table->double('total_qty')->nullable();
            $table->double('total_tax')->nullable();
            $table->double('total_cost')->nullable();
            $table->double('shipping_cost')->default(0);
            $table->double('grand_total')->nullable();
            $table->string('document')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('sale_transfers');
    }
}
