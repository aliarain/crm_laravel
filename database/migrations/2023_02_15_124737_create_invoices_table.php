<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
 

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('sent')->default(0);
            $table->dateTime('datesend')->nullable();
            $table->integer('clientid');
            $table->string('deleted_customer_name', 100)->nullable();
            $table->integer('number');
            $table->string('prefix', 50)->nullable();
            $table->integer('number_format')->default(0);
            $table->dateTime('datecreated');
            $table->date('date');
            $table->date('duedate')->nullable();
            $table->integer('currency');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('total_tax', 15, 2)->default(0.00);
            $table->decimal('total', 15, 2);
            $table->decimal('adjustment', 15, 2)->nullable();
            $table->integer('addedfrom')->nullable();
            $table->string('hash', 32);
            $table->integer('status')->default(1);
            $table->text('clientnote')->nullable();
            $table->text('adminnote')->nullable();
            $table->date('last_overdue_reminder')->nullable();
            $table->date('last_due_reminder')->nullable();
            $table->integer('cancel_overdue_reminders')->default(0);
            $table->mediumText('allowed_payment_modes')->nullable();
            $table->mediumText('token')->nullable();
            $table->decimal('discount_percent', 15, 2)->default(0.00);
            $table->decimal('discount_total', 15, 2)->default(0.00);
            $table->string('discount_type', 30);
            $table->integer('recurring')->default(0);
            $table->string('recurring_type', 10)->nullable();
            $table->tinyInteger('custom_recurring')->default(0);
            $table->integer('cycles')->default(0);
            $table->integer('total_cycles')->default(0);
            $table->integer('is_recurring_from')->nullable();
            $table->date('last_recurring_date')->nullable();
            $table->text('terms')->nullable();
            $table->integer('sale_agent')->default(0);
            $table->string('billing_street', 200)->nullable();
            $table->string('billing_city', 100)->nullable();
            $table->string('billing_state', 100)->nullable();
            $table->string('billing_zip', 100)->nullable();
            $table->integer('billing_country')->nullable();
            $table->string('shipping_street', 200)->nullable();
            $table->string('shipping_city', 100)->nullable();
            $table->string('shipping_state', 100)->nullable();
            $table->string('shipping_zip', 100)->nullable();
            $table->integer('shipping_country')->nullable();
            $table->tinyInteger('include_shipping')->default(0);
            $table->tinyInteger('show_shipping_on_invoice')->default(1);
            $table->integer('show_quantity_as')->default(1);
            $table->integer('project_id')->default(0);
            $table->integer('subscription_id')->default(0);
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
        Schema::dropIfExists('invoices');
    }
}
