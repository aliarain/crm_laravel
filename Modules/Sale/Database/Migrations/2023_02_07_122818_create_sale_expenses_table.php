<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no');
            $table->integer('expense_category_id');
            $table->integer('warehouse_id');
            $table->integer('account_id');
            $table->integer('user_id');
            $table->integer('cash_register_id');
            $table->double('amount');
            $table->text('note')->nullable();
            $table->foreignId('created_by')->index('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->index('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });


          if (config('app.APP_CRM')){
            $expenseCategories = DB::table('sale_expense_categories')->get();
            foreach ($expenseCategories as $key => $value) {
                
                DB::table('sale_expenses')->insert([
                    'reference_no' => rand(1000000000, 9999999999), 
                    'expense_category_id' => $value->id,
                    'warehouse_id' => 1,
                    'account_id' => 1,
                    'user_id' => 1,
                    'cash_register_id' => 1,
                    'amount' => 100,
                    'note' => 'test',
                    'created_at' => $value->created_at,
                    'updated_at' => $value->updated_at,

                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_expenses');
    }
}
