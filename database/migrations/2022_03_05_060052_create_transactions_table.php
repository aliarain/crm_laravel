<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('accounts')->nullable()->default(1)->cascadeOnDelete();

            
            $table->date('date')->nullable();
            $table->string('description', 400)->nullable();
            $table->double('amount',16,2)->default(0);
            $table->foreignId('transaction_type')->index('transaction_type')->default(18)->constrained('statuses')->comment('18=Debit, 19=Credit');
            $table->foreignId('client_id')->index('client_id')->nullable()->constrained('clients')->cascadeOnDelete();
            

            $table->foreignId('income_expense_category_id')->index('income_expense_category_id')->nullable()->constrained('income_expense_categories')->cascadeOnDelete();

            $table->foreignId('status_id')->index('status_id')->default(9)->constrained('statuses')->comment('9=Unpaid, 8=Paid', '18 = Partial Paid');

            $table->foreignId('created_by')->index('created_by')->default(1)->constrained('users');
            $table->foreignId('updated_by')->index('updated_by')->default(1)->constrained('users');

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
        Schema::dropIfExists('transactions');
    }
}
