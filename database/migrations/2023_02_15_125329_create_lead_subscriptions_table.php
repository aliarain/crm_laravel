<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('description_in_item')->default(false);
            $table->unsignedBigInteger('clientid');
            $table->date('date')->nullable();
            $table->text('terms')->nullable();
            $table->unsignedBigInteger('currency');
            $table->unsignedBigInteger('tax_id')->default(0);
            $table->string('stripe_tax_id')->nullable();
            $table->unsignedBigInteger('tax_id_2')->default(0);
            $table->string('stripe_tax_id_2')->nullable();
            $table->text('stripe_plan_id')->nullable();
            $table->text('stripe_subscription_id');
            $table->bigInteger('next_billing_cycle')->nullable();
            $table->bigInteger('ends_at')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('quantity')->default(1);
            $table->unsignedBigInteger('project_id')->default(0);
            $table->string('hash', 32); 
            $table->unsignedBigInteger('created_from');
            $table->timestamp('date_subscribed')->nullable();
            $table->unsignedBigInteger('in_test_environment')->nullable();
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
        Schema::dropIfExists('lead_subscriptions');
    }
}
