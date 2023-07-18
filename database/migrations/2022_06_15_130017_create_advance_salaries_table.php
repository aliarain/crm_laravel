<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvanceSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advance_salaries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('advance_type_id')->index('advance_type_id')->default(1)->constrained('advance_types');  
            
            $table->foreignId('user_id')->index('user_id')->default(1)->constrained('users');  

            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->date('date');
            $table->double('amount',16,2)->nullable();
            $table->double('request_amount',16,2)->default(0);
            $table->double('paid_amount',16,2)->default(0);
            $table->double('due_amount',16,2)->default(0);
            $table->tinyInteger('recovery_mode')->default(1)->comment('1=Installment, 2=One Time');
            $table->tinyInteger('recovery_cycle')->default(1)->comment('1=Monthly, 2=Yearly');
            $table->double('installment_amount',16,2)->default(0);
            $table->date('recover_from');
            
            $table->foreignId('pay')->index('pay')->default(9)->constrained('statuses')->comment('9=Unpaid, 8=Paid');

            $table->foreignId('status_id')->index('status_id')->default(2)->constrained('statuses')->comment('2=Pending, 5=Approved, 6=Rejected');
            $table->foreignId('approver_id')->nullable()->index('approver_id')->constrained('users');
            $table->text('remarks')->nullable();            
            $table->foreignId('return_status')->index('return_status')->default(22)->constrained('statuses')->comment('22=No, 21=Partially Returned, 23=Returned');
            $table->foreignId('created_by')->index('created_by')->default(1)->constrained('users');
            $table->foreignId('updated_by')->index('updated_by')->default(1)->constrained('users');

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
        Schema::dropIfExists('advance_salaries');
    }
}
