<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();
            

            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('expense_claim_id')->constrained('expense_claims')->cascadeOnDelete();

            $table->string('code',191)->unique()->nullable()->comment('invoice number');
            $table->date('payment_date')->nullable()->comment('date of payment');

            $table->string('remarks',191)->nullable()->comment('remarks of payment');

            $table->double('payable_amount',10,2)->nullable()->comment('amount of payment');
            $table->double('paid_amount',10,2)->nullable()->comment('paid amount of payment');
            $table->double('due_amount',10,2)->nullable()->comment('due amount of payment');
            

            $table->foreignId('attachment_file_id')->nullable()->constrained('uploads')->cascadeOnDelete();
            $table->foreignId('payment_status_id')->index('status_id')->constrained('statuses');
            

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
        Schema::dropIfExists('payment_histories');
    }
}
