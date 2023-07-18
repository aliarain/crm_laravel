<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvanceSalaryLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advance_salary_logs', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 16, 2);
            $table->double('due_amount', 16, 2)->nullable();
            $table->tinyInteger('is_pay')->default(0)->comment('0=Company Pay, 1= Staff Pay');
            $table->foreignId('advance_salary_id')->index('advance_salary_id')->default(1)->constrained('advance_salaries');  

            $table->foreignId('user_id')->index('user_id')->default(1)->constrained('users');  

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
        Schema::dropIfExists('advance_salary_logs');
    }
}
