<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_products', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->bigInteger('serial')->nullable(); 
            $table->string('name', 200)->nullable();  
            $table->foreignId('status_id')->index('status_id')->constrained('statuses')->comment('1=active,4=inactive');
            $table->foreignId('author_id')->index('author_id')->constrained('users');
            $table->foreignId('stock_brand_id')->index('stock_brand_id')->constrained('stock_brands');
            $table->foreignId('stock_category_id')->index('category_id')->constrained('stock_categories');
            $table->foreignId('avatar_id')->nullable()->constrained('uploads');
            $table->string('tags', 1000)->nullable();
            $table->longtext('description')->nullable();
            $table->double('unit_price', 16, 2)->nullable();
            $table->bigInteger('total_quantity')->nullable()->default('0');
            $table->integer('published')->default('0')->comment('0=unpublished,1=published,2=rejected,3=archived,4=deleted');
            


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
        Schema::dropIfExists('stock_products');
    }
}
