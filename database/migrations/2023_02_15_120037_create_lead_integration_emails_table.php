<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadIntegrationEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
 
        Schema::create('lead_integration_emails', function (Blueprint $table) {
            $table->id();
            $table->text('subject')->nullable();
            $table->text('body')->nullable();
            $table->dateTime('dateadded');
            $table->integer('leadid');
            $table->integer('emailid');
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
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
        Schema::dropIfExists('lead_integration_emails');
    }
}
