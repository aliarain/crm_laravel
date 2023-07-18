<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('image')->nullable();
            $table->integer('parent_id')->nullable();
            $table->boolean('is_active')->nullable();
            // $table->foreignId('created_by')->index('created_by')->nullable()->constrained('users');
            // $table->foreignId('updated_by')->index('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        // Sale Category Seeding Start
        if (config('app.APP_CRM')){
            $categories = [ "Electronics",  "Fashion",  "Home & Kitchen",  "Health & Beauty",  "Sports & Outdoors",  "Books & Stationery",  "Toys & Games",  "Music & Movies",  "Vehicles & Parts",  "Food & Beverages"];

            $data = [];
            foreach($categories as $category){
                $data[] = [
                    'name' => $category,
                    'slug' => Str::slug($category),
                    'image' => null,
                    'parent_id' => null,
                    'is_active' => true, 
                ];
            }

            DB::table('sale_categories')->insert($data);
        }
        // Sale Category Seeding End

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_categories');
    }
}
