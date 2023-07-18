<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppoinmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appoinments', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('company_id')->constrained('companies'); 
            $table->foreignId('created_by')->constrained('users'); 
            $table->foreignId('appoinment_with')->constrained('users'); 
            $table->string('appoinment_start_at')->nullable();
            $table->string('appoinment_end_at')->nullable();
            $table->string('title',255)->nullable();
            $table->string('location',255)->nullable();
            $table->text('description')->nullable(); 
            $table->date('date')->nullable(); 
            
            
            $table->foreignId('attachment_file_id')->nullable()->constrained('uploads')->cascadeOnDelete();
            $table->foreignId('status_id')->index('status_id')->default(1)->constrained('statuses');
            
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
        Schema::dropIfExists('appoinments');
    }
}
