<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->date('date')->nullable();

            $table->double('distance', 10, 2)->comment('in km')->nullable();
            $table->double('latitude')->comment('latitude')->nullable();
            $table->double('longitude')->comment('longitude')->nullable();
            $table->double('speed')->comment('speed')->nullable();
            $table->string('heading')->comment('heading')->nullable();
            $table->string('city')->comment('city')->nullable();
            $table->string('address')->comment('address')->nullable();
            $table->string('countryCode')->comment('countryCode')->nullable();
            $table->string('country')->comment('country')->default('Bangladesh')->nullable();


            $table->timestamps();

            $table->index(['id', 'user_id', 'company_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_logs');
    }
}
