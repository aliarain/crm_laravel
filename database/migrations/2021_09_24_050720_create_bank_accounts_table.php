<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained('users');
            $table->string('name',191);
            $table->string('account_number')->comment('Account Number')->unique();
            $table->string('bank_name')->comment('Bank Name');
            $table->string('branch_name')->comment('Bank branch name'); 
            $table->string('ifsc_code')->comment('IFSC Code')->nullable();
            $table->enum('account_type', ['savings', 'current'])->default('savings');

            $table->string('account_holder_name',191)->nullable();
            $table->string('account_holder_mobile',191)->nullable();
            $table->string('account_holder_email',191)->nullable();

            $table->foreignId('status_id')->index()->nullable()->constrained('statuses')->cascadeOnDelete()->comment('Status of the day');
            $table->foreignId('author_info_id')->index()->nullable()->constrained('author_infos')->cascadeOnDelete()->comment('Author info of the day');

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
        Schema::dropIfExists('bank_accounts');
    }
}
