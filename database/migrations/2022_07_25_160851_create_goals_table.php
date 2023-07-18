<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('subject')->nullable();
            $table->string('target')->nullable();
            $table->foreignId('goal_type_id')->nullable()->constrained('goal_types')->cascadeOnDelete();

            $table->integer('progress')->nullable()->default(0);

            $table->foreignId('status_id')->index('status_id')->default(24)->constrained('statuses')->comment('24 = Not Started', '26 = In Progress', '27 = Completed');

            

            $table->longText('description')->nullable();
            //start_date
            $table->date('start_date');
            //end_date
            $table->date('end_date');

            $table->integer('rating')->nullable()->default(0);

            $table->foreignId('created_by')->index('created_by')->default(1)->constrained('users');



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
        Schema::dropIfExists('goals');
    }
}
