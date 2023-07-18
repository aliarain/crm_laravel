<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->string('name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->integer('total_employee')->nullable();
            $table->string('business_type')->nullable();
            $table->string('trade_licence_number')->nullable();
            $table->foreignId('trade_licence_id')->nullable()->index('trade_licence_id')->constrained('uploads');
            $table->foreignId('status_id')->index('status_id')->default(1)->constrained('statuses');
            $table->enum('is_main_company', ['yes', 'no'])->default('no');
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
        Schema::dropIfExists('companies');
    }
}
