<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('show_to_clients')->index('show_to_clients')->default(22)->constrained('statuses')->comment('22=No,32=Yes');
            $table->string('path');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Who created the attachment
            $table->foreignId('status_id')->index('status_id')->default(1)->constrained('statuses')->comment('1=active,4=inactive');
            $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
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
        Schema::dropIfExists('lead_attachments');
    }
}
