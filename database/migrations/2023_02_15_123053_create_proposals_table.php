<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string('subject', 191)->nullable();
            $table->longText('content')->nullable();
            $table->integer('addedfrom');
            $table->dateTime('datecreated');
            $table->decimal('total', 15, 2)->nullable();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('total_tax', 15, 2)->default(0.00);
            $table->decimal('adjustment', 15, 2)->nullable();
            $table->decimal('discount_percent', 15, 2);
            $table->decimal('discount_total', 15, 2);
            $table->string('discount_type', 30)->nullable();
            $table->integer('show_quantity_as')->default(1);
            $table->integer('currency');
            $table->date('open_till')->nullable();
            $table->date('date');
            $table->integer('rel_id')->nullable();
            $table->string('rel_type', 40)->nullable();
            $table->integer('assigned')->nullable();
            $table->string('hash', 32);
            $table->string('proposal_to', 191)->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('country')->default(0);
            $table->string('zip', 50)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('address', 200)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('phone', 50)->nullable();
            $table->boolean('allow_comments')->default(1);
            $table->integer('status');
            $table->integer('estimate_id')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->dateTime('date_converted')->nullable();
            $table->integer('pipeline_order')->default(1);
            $table->integer('is_expiry_notified')->default(0);
            $table->string('acceptance_firstname', 50)->nullable();
            $table->string('acceptance_lastname', 50)->nullable();
            $table->string('acceptance_email', 100)->nullable();
            $table->dateTime('acceptance_date')->nullable();
            $table->string('acceptance_ip', 40)->nullable();
            $table->string('signature', 40)->nullable();
            $table->string('short_link', 100)->nullable();
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
        Schema::dropIfExists('proposals');
    }
}
