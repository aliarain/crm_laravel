<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_brands', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->nullable();
            $table->foreignId('created_by')->index('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->index('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        // Sale Brands Seeding Start
        if (config('app.APP_CRM')){
            $brands = ["Nike", "Adidas", "Puma", "Calvin Klein", "Levi's", "Tommy Hilfiger", "Zara", "Gap", "H&M", "Forever 21", "Walmart", "Target", "Costco", "Kroger", "Whole Foods Market", "Aldi", "CVS Pharmacy", "Walgreens"];

            $data = [];
            foreach($brands as $brand){
                $data[] = [
                    'title' => $brand,
                    'slug' => Str::slug($brand),
                    'image' => null,
                    'is_active' => true, 
                    ];
            }

            DB::table('sale_brands')->insert($data);
        }
        // Sale Brands Seeding End
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_brands');
    }
}
