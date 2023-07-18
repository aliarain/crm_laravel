<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('lead_activity_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lead_type_id')->constrained('lead_types')->cascadeOnDelete(); // must required
            $table->foreignId('lead_source_id')->constrained('lead_sources')->cascadeOnDelete(); // must required
            $table->foreignId('lead_status_id')->constrained('lead_statuses')->cascadeOnDelete(); // must required
            $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete(); // must required

            // basic activity logs
            $table->text('title');
            $table->longText('description')->nullable(); 
            $table->dateTime('date');
            $table->dateTime('next_follow_up')->nullable();


            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->date('assigned_date')->nullable();




            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete(); 
            $table->foreignId('status_id')->index('status_id')->constrained('statuses')->comment('1=active,4=inactive');

            $table->timestamps();
        });

        // seeder LeadActivityLog 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_activity_logs');
    }
}
