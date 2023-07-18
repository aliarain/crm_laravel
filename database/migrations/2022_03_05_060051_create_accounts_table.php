<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('name', 200)->nullable();
            $table->string('ac_name', 200)->nullable();
            $table->string('ac_number', 100)->nullable();
            $table->string('code', 200)->nullable();
            $table->string('branch', 200)->nullable();

            $table->double('amount',16,2)->default(0);

            $table->foreignId('status_id')->index('status_id')->default(1)->constrained('statuses')->comment('1=Active,4=Inactive');


         
            $table->foreignId('created_by')->index('created_by')->default(1)->constrained('users');
            $table->foreignId('updated_by')->index('updated_by')->default(1)->constrained('users');
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
