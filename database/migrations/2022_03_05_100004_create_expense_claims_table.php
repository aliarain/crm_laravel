<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_claims', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('invoice_number', 191)->unique()->nullable()->comment('invoice number');
            $table->date('claim_date')->nullable()->comment('date of claim');

            $table->string('remarks', 191)->nullable()->comment('remarks of payment');

            $table->double('payable_amount', 10, 2)->nullable()->comment('amount of payment');
            $table->double('due_amount', 10, 2)->nullable()->comment('due amount of payment');

            $table->foreignId('attachment_file_id')->nullable()->constrained('uploads')->cascadeOnDelete();
            $table->foreignId('status_id')->index('status_id')->default(1)->constrained('statuses');

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
        Schema::dropIfExists('expense_claims');
    }
}
