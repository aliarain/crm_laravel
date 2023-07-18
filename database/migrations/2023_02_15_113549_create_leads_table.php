<?php

use App\Models\Leads\Lead;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        


        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_type_id')->constrained('lead_types')->cascadeOnDelete(); // must required
            $table->foreignId('lead_source_id')->constrained('lead_sources')->cascadeOnDelete(); // must required
            $table->foreignId('lead_status_id')->constrained('lead_statuses')->cascadeOnDelete(); // must required

            $table->string('name', 191)->nullable(); 
            $table->string('company', 191)->nullable();

            $table->string('title', 100)->nullable();
            $table->text('description')->nullable();

            // address information
            $table->integer('country')->default(0);
            $table->string('state', 50)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('zip', 15)->nullable();
            $table->string('address', 100)->nullable();

            // online information
            $table->string('email', 100)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('website', 150)->nullable();

            //date information
            $table->date('date')->nullable();
            $table->date('next_follow_up')->nullable();

            //assigned to client
            $table->unsignedBigInteger('client_id')->nullable(); // if lead is converted to client


            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete(); 
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete(); 
            $table->foreignId('status_id')->index('status_id')->constrained('statuses')->comment('1=active,4=inactive');
            $table->timestamps();


            $table->json('attachments')->nullable()->comment('index,title, path,author,date,size');
            $table->json('emails')->nullable()->comment('index, date, subject, body, from, to, cc, bcc');
            $table->json('calls')->nullable()->comment('index, date, duration, type, subject, body, number,author');
            $table->json('activities')->nullable()->comment('index, date, status, author, message');
            $table->json('notes')->nullable()->comment('index, date, author, subject, message');
            $table->json('tasks')->nullable()->comment('index, date, status, author, subject, message');
            $table->json('reminders')->nullable()->comment('index, date, status, author, subject, message');
            $table->json('tags')->nullable()->comment('index, name');
            $table->json('deals')->nullable()->comment('index, name, amount, date, status, author, subject, message');


        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
}
