<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('title', 191);
            $table->integer('order')->default(0);
            $table->string('border_color', 7)->default('#000000');
            $table->string('background_color', 7)->default('#ffffff');
            $table->string('text_color', 7)->default('#000000');
            $table->foreignId('status_id')->index('status_id')->constrained('statuses')->comment('1=active,4=inactive');

            $table->foreignId('created_by')->constrained('users')->default(1)->nullable();
            $table->foreignId('updated_by')->constrained('users')->default(1)->nullable();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();

            $table->timestamps();
        });
        // seeder LeadStatusSeeder
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_statuses');
    }
}
