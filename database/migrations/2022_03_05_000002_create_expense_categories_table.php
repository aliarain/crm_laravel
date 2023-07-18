<?php

use App\Models\Company\Company;
use Illuminate\Support\Facades\Schema;
use App\Models\Expenses\IncomeExpenseCategory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_expense_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('name', 191);
            $table->tinyInteger('is_income')->default(0)->comment('0=Expense, 1=Income');
            $table->foreignId('attachment_file_id')->nullable()->constrained('uploads');
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
        Schema::dropIfExists('income_expense_categories');
    }
}
