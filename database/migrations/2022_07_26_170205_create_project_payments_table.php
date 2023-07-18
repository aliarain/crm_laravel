<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_payments', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 16, 2);
            $table->double('due_amount', 16, 2)->nullable();
            
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();

            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            
            $table->foreignId('transaction_id')->nullable()->index('transaction_id')->constrained('transactions');

            $table->foreignId('payment_method_id')->nullable()->index('payment_method_id')->constrained('payment_methods');

            $table->foreignId('paid_by')->index('paid_by')->default(1)->constrained('clients');  



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
        Schema::dropIfExists('project_payments');
    }
}
