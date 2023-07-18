<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->date('date')->nullable();
            //progressBar
            $table->integer('progress')->nullable()->default(0);

            $table->foreignId('status_id')->index('status_id')->default(24)->constrained('statuses')->comment('24 = Not Started , 25 = On Hold', '26 = In Progress', '27 = Completed', '28 = Cancelled');

            $table->foreignId('priority')->index('priority')->default(24)->constrained('statuses')->comment('29 = Urgent , 30 = High', '29 = Medium', '28 = Low');
            

            $table->longText('description');
            //start_date
            $table->date('start_date');
            //end_date
            $table->date('end_date');

            $table->foreignId('created_by')->index('created_by')->default(1)->constrained('users');

            // notifying all users when project is created
            $table->tinyInteger('notify_all_users')->default(0)->comment('0=no,1=yes');

            // notifying all users via email when project is created

            $table->tinyInteger('notify_all_users_email')->default(0)->comment('0=no,1=yes');
            // task type
            $table->tinyInteger('type')->default(0)->comment('0=Regular , 1= Project');

            $table->foreignId('client_id')->nullable()->index('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->index('project_id')->constrained('projects')->cascadeOnDelete();
            
            $table->tinyInteger('reminder')->default(0)->comment('0=no,1=yes');
            // goal
            $table->foreignId('goal_id')->nullable()->constrained('goals')->cascadeOnDelete();

            $table->timestamps();

            $table->index(['id', 'company_id', 'status_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
