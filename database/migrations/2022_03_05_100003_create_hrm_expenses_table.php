<?php

use App\Models\User;
use App\Models\Company\Company;
use App\Models\Expenses\HrmExpense;
use Illuminate\Support\Facades\Schema;
use App\Models\Expenses\IncomeExpenseCategory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // expense table
        Schema::create('hrm_expenses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('income_expense_category_id')->constrained('income_expense_categories')->cascadeOnDelete();

            $table->date('date')->nullable()->comment('date of expense');
            $table->string('remarks', 191)->nullable()->comment('remarks of expense');
            $table->double('amount', 10, 2)->nullable()->comment('amount of expense');

            $table->foreignId('attachment_file_id')->nullable()->constrained('uploads')->cascadeOnDelete();
            $table->foreignId('status_id')->index('status_id')->default(1)->constrained('statuses');

            $table->foreignId('is_claimed_status_id')->index('claimed_status_id')->constrained('statuses');
            $table->foreignId('claimed_approved_status_id')->index('claimed_approved_status_id')->constrained('statuses');

            $table->softDeletes();
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
        Schema::dropIfExists('hrm_expenses');
    }
}
