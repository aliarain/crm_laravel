<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_setups', function (Blueprint $table) {
            $table->id();
            $table->string('host_name')->nullable();
            $table->string('key')->nullable();
            $table->string('value')->nullable();
            $table->foreignId('status_id')->index('status_id')->default(1)->constrained('statuses');
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
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
        Schema::dropIfExists('meeting_setups');
    }
}
