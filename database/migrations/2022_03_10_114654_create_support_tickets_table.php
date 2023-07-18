<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->date('date')->nullable();
            $table->string('code', 191);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('assigned_id')->nullable()->constrained('users');
            $table->foreignId('attachment_file_id')->nullable()->constrained('uploads')->cascadeOnDelete();
            $table->string('subject', 191)->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('status_id')->default(1)->constrained('statuses');
            $table->foreignId('type_id')->default(12)->comment('12 = open , 13 = close')->constrained('statuses');
            $table->foreignId('priority_id')->default(14)->comment('14 = high , 15 = medium , 16 = low')->constrained('statuses');
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
        Schema::dropIfExists('support_tickets');
    }
}
