<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicePaymentRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_payment_records', function (Blueprint $table) {
            $table->id();
            $table->integer('invoiceid');
            $table->decimal('amount', 15, 2);
            $table->string('paymentmode', 40)->nullable();
            $table->string('paymentmethod', 191)->nullable();
            $table->date('date');
            $table->dateTime('daterecorded');
            $table->text('note');
            $table->mediumText('transactionid')->nullable();
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
        Schema::dropIfExists('invoice_payment_records');
    }
}
