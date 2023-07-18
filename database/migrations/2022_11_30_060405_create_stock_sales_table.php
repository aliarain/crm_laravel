<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_sales', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('stock_product_id')->constrained('stock_products')->cascadeOnDelete();
            $table->foreignId('stock_payment_history_id')->constrained('stock_payment_histories')->cascadeOnDelete();
            
            $table->string('invoice')->nullable();
            $table->date('date')->nullable();
         
            
            $table->foreignId('status_id')->constrained('statuses')->cascadeOnDelete()->comment('1= Active, 2= Pending, 3= Suspended, 4= Inactive, 5= Approve, 6= Reject, 7= Cancel');
            $table->foreignId('payment_status_id')->constrained('statuses')->cascadeOnDelete()->comment('8=paid 9=unpaid, 10=claimed, 11=not claimed');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('deleted_by')->constrained('users')->cascadeOnDelete();
            $table->softDeletes();

            
            $table->double('price',16,2);
            $table->double('discount',16,2);
            $table->double('tax',16,2);
            $table->double('total',16,2);

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
        Schema::dropIfExists('stock_sales');
    }
}
