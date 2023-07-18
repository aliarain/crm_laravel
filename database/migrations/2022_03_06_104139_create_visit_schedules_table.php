<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->foreignId('visit_id')->constrained('visits')->cascadeOnDelete();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->longText('note')->nullable();
            $table->enum('status', ['created','started', 'reached','end'])->default('created');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('reached_at')->nullable();
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
        Schema::dropIfExists('visit_schedules');
    }
}
