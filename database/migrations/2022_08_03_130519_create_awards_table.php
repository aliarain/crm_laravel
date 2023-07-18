<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('award_type_id')->constrained('award_types')->cascadeOnDelete();
            $table->date('date')->nullable();
            $table->string('gift')->nullable();
            $table->double('amount',16,2)->nullable();
            $table->string('gift_info')->nullable();
            $table->longText('description');
            $table->foreignId('status_id')->index('status_id')->default(1)->constrained('statuses')->comment('1=active,4=inactive');
            $table->foreignId('attachment')->nullable()->constrained('uploads');
            // goal
            $table->foreignId('goal_id')->nullable()->constrained('goals')->cascadeOnDelete();
            $table->timestamps();
            $table->index(['id', 'award_type_id', 'company_id', 'status_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('awards');
    }
}
