<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('type_id')->constrained('leave_types')->cascadeOnDelete();
            $table->integer('days');
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->foreignId('status_id')->constrained('statuses');
            $table->timestamps();

            // add soft deletes
            $table->softDeletes();
            // index
            $table->index(['company_id', 'type_id', 'department_id', 'status_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assign_leaves');
    }
}
