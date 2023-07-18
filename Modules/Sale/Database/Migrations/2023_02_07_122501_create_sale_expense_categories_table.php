<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleExpenseCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->boolean('is_active');
            $table->foreignId('created_by')->index('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->index('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        // env app sync check 
          if (config('app.APP_CRM')){
            $categories = ['Housing', 'Transportation', 'Food', 'Utilities', 'Insurance', 'Medical & Healthcare'];
            foreach ($categories as $category) {
                DB::table('sale_expense_categories')->insert([
                    'code' => rand(100000, 999999), 
                    'name' => $category,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(), 
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
        Schema::dropIfExists('sale_expense_categories');
    }
}
