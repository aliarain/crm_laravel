<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalarySetupDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_setup_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('user_id')->index('user_id')->default(1)->constrained('users');  
            $table->foreignId('salary_setup_id')->index('salary_setup_id')->default(1)->constrained('salary_setups');  
            $table->foreignId('commission_id')->index('commission_id')->default(1)->constrained('commissions');  

            $table->double('amount', 16, 2);
            $table->tinyInteger('amount_type')->default(1)->comment('1=fixed, 2=percentage');
            
            $table->foreignId('status_id')->index('status_id')->default(1)->constrained('statuses')->comment('1=Active,4=Inactive');

         
            $table->foreignId('created_by')->index('created_by')->default(1)->constrained('users');
            $table->foreignId('updated_by')->index('updated_by')->default(1)->constrained('users');

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
        Schema::dropIfExists('salary_setup_details');
    }
}
