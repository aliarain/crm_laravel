<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('meeting_id')->constrained('meetings')->cascadeOnDelete();
            $table->tinyInteger('is_going')->default(0)->comment('0: Not going, 1: Going');
            $table->tinyInteger('is_present')->default(0)->comment('0: Absent, 1: Present');
            $table->dateTime('present_at')->nullable();
            $table->dateTime('meeting_started_at')->nullable();
            $table->dateTime('meeting_ended_at')->nullable();
            $table->time('meeting_duration')->nullable()->comment('Meeting duration in minutes');
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
        Schema::dropIfExists('meeting_participants');
    }
}
