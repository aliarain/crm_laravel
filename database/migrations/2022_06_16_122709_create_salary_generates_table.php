<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryGeneratesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_generates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->index('user_id')->default(1)->constrained('users');  

            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->date('date');
            $table->double('amount',16,2);
            $table->double('due_amount',16,2)->nullable();
            $table->double('gross_salary',16,2);
            $table->foreignId('status_id')->index('status_id')->default(9)->constrained('statuses')->comment('9=Unpaid, 8=Paid', '20 = Partial Paid');
            $table->foreignId('created_by')->index('created_by')->default(1)->constrained('users');
            $table->foreignId('updated_by')->index('updated_by')->default(1)->constrained('users');

            $table->integer('total_working_day')->nullable();
            $table->integer('present')->nullable();
            $table->integer('absent')->nullable();
            $table->integer('late')->nullable();
            $table->integer('left_early')->nullable();

            $table->tinyInteger('is_calculated')->default(0);


            $table->double('adjust',16,2)->nullable();
            $table->double('absent_amount',16,2)->nullable();


            $table->double('advance_amount',16,2)->nullable();
            $table->json('advance_details')->nullable();

            $table->double('allowance_amount',16,2)->nullable();
            $table->json('allowance_details')->nullable();
            $table->double('deduction_amount',16,2)->nullable();
            $table->json('deduction_details')->nullable();

            $table->double('net_salary',16,2)->nullable();



            $table->timestamps();

            $table->index(['id', 'amount', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_generates');
    }
}
