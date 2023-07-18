<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_sources', function (Blueprint $table) {
            $table->id();
            $table->string('title', 191);
            $table->bigInteger('order')->default(0);
            $table->foreignId('status_id')->index('status_id')->constrained('statuses')->comment('1=active,4=inactive');
            
            $table->foreignId('created_by')->constrained('users')->default(1)->nullable();
            $table->foreignId('updated_by')->constrained('users')->default(1)->nullable();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->timestamps();
        });
        // seeder LeadSourceSeeder
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_sources');
    }
}
