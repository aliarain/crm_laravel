<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentHistoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_history_details', function (Blueprint $table) {
            $table->id();


            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('payment_history_id')->constrained('payment_histories')->cascadeOnDelete();


            $table->foreignId('payment_method_id')->constrained('payment_methods')->cascadeOnDelete();
            $table->longText('payment_details')->nullable()->comment('remarks of payment');
            $table->foreignId('payment_status_id')->index('payment_status_id')->constrained('statuses');
            $table->date('payment_date')->nullable()->comment('date of payment');
            $table->foreignId('paid_by_id')->nullable()->constrained('users')->cascadeOnDelete();


            // banks 
            $table->string('bank_name',191)->nullable()->comment('bank name');
            $table->string('bank_branch',191)->nullable()->comment('bank branch');
            $table->string('bank_account_number',191)->nullable()->comment('bank account number');
            $table->string('bank_account_name',191)->nullable()->comment('bank account name');
            $table->string('bank_transaction_number',191)->nullable()->comment('bank transaction number');
            $table->date('bank_transaction_date')->nullable()->comment('bank transaction date');
            $table->string('bank_transaction_ref',191)->nullable()->comment('bank transaction ref');


            // cheque 
            $table->string('cheque_number',191)->nullable()->comment('cheque number');
            $table->date('cheque_date')->nullable()->comment('cheque date');
            $table->string('cheque_bank_name',191)->nullable()->comment('cheque bank name');
            $table->string('cheque_branch',191)->nullable()->comment('cheque branch');
            $table->string('cheque_ref',191)->nullable()->comment('cheque ref');
            
            
            // cash
            $table->string('cash_number',191)->nullable()->comment('cash number');
            $table->date('cash_date')->nullable()->comment('cash date');
            $table->string('cash_ref',191)->nullable()->comment('cash ref'); 


            $table->double('paid_amount', 15, 2)->default(0);
            $table->double('due_amount', 15, 2)->default(0);
           
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
        Schema::dropIfExists('payment_history_details');
    }
}
