<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVirtualMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->integer('duration')->nullable()->default(0)->comment('0 means unlimited');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->string('host')->default('jitsi');
            $table->text('description')->nullable();
            $table->text('datetime')->nullable()->default(null);
            $table->foreignId('status_id')->index('status_id')->default(1)->constrained('statuses');
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
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
        Schema::dropIfExists('virtual_meetings');
    }
}
