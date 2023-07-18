<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentHistoryLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_history_logs', function (Blueprint $table) {
            $table->id();


            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('payment_history_id')->constrained('payment_histories')->cascadeOnDelete();
            $table->foreignId('expense_claim_id')->constrained('expense_claims')->cascadeOnDelete();

            $table->double('payable_amount',10,2)->nullable()->comment('amount of payment');
            $table->double('paid_amount',10,2)->nullable()->comment('paid amount of payment');
            $table->double('due_amount',10,2)->nullable()->comment('due amount of payment');
            $table->date('date')->nullable();
            
            $table->foreignId('paid_by_id')->constrained('users')->cascadeOnDelete();
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
        Schema::dropIfExists('payment_history_logs');
    }
}
