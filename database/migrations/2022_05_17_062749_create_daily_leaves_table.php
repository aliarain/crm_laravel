<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('approved_by_tl')->nullable()->constrained('users')->cascadeOnDelete();
            $table->timestamp('approved_at_tl')->nullable();
            
            $table->foreignId('approved_by_hr')->nullable()->constrained('users')->cascadeOnDelete();
            $table->timestamp('approved_at_hr')->nullable();

            $table->foreignId('rejected_by_tl')->nullable()->constrained('users')->cascadeOnDelete();
            $table->timestamp('rejected_at_tl')->nullable();

            $table->foreignId('rejected_by_hr')->nullable()->constrained('users')->cascadeOnDelete();
            $table->timestamp('rejected_at_hr')->nullable();
            
            $table->enum('leave_type', ['early_leave', 'late_arrive'])->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->text('reason')->nullable();
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
        Schema::dropIfExists('daily_leaves');
    }
}
