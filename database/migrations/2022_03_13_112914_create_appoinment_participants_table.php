<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppoinmentParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appoinment_participants', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('participant_id')->constrained('users'); 
            $table->foreignId('appoinment_id')->constrained('appoinments');
            $table->tinyInteger('is_agree')->default(0)->comment('0: Not agree, 1: Agree');
            $table->tinyInteger('is_present')->default(0)->comment('0: Absent, 1: Present');
            $table->dateTime('present_at')->nullable();
            $table->dateTime('appoinment_started_at')->nullable();
            $table->dateTime('appoinment_ended_at')->nullable();
            $table->string('appoinment_duration')->nullable()->comment('appoinment duration in minutes');
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
        Schema::dropIfExists('appoinment_participants');
    }
}
