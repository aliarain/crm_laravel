<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockPaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_payment_histories', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->enum('type', ['purchase', 'sale'])->nullable();
            //cash payment
            $table->double('amount',16,2);
            $table->string('reference')->nullable();
            
            $table->integer('payment_type')->comment('1=cash,2=bank,3=online,4=cheque');
            $table->foreignId('bank_id')->constrained('bank_accounts')->cascadeOnDelete()->nullable();
            $table->foreignId('payment_method_id')->constrained('payment_methods')->cascadeOnDelete()->nullable();
            
            

            //bank payment
            $table->string('bank_reference')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_account_holder')->nullable();

            //checque payment
            $table->string('cheque_number')->nullable();
            $table->string('cheque_date')->nullable();
            $table->string('cheque_bank')->nullable();
            $table->string('cheque_branch')->nullable();
            $table->string('cheque_account_holder')->nullable();


            //online payment
            $table->string('email')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_date')->nullable();
              
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
        Schema::dropIfExists('stock_payment_histories');
    }
}
