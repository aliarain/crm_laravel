<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryPaymentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_payment_logs', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 16, 2);
            $table->double('due_amount', 16, 2)->nullable();
            $table->bigInteger('salary_generate_id')->unsigned()->nullable();
            $table->foreign('salary_generate_id')->references('id')->on('salary_generates')->onDelete('cascade');

            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            
            $table->foreignId('transaction_id')->nullable()->index('transaction_id')->constrained('transactions');

            $table->foreignId('payment_method_id')->nullable()->index('payment_method_id')->constrained('payment_methods');

            $table->foreignId('paid_by')->index('paid_by')->default(1)->constrained('users');  


            $table->foreignId('user_id')->index('user_id')->nullable()->constrained('users');

            $table->foreignId('created_by')->index('created_by')->default(1)->constrained('users');
            $table->foreignId('updated_by')->index('updated_by')->default(1)->constrained('users');

            $table->text('payment_note')->nullable();

            $table->timestamps();

            $table->index(['id', 'amount']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_payment_logs');
    }
}
