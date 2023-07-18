<?php

namespace Database\Seeders;

use App\Models\StockBrand;
use App\Models\StockProduct;
use App\Models\StockCategory;
use Illuminate\Database\Seeder;

class StockProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = StockCategory::all();
        $brands = StockBrand::all();


        foreach ($categories as $key1 => $category) {
            foreach ($brands as $key2 => $brand) {

                $new = new StockProduct();
                $new->company_id = 1;
                $new->serial = 'serial-' . $key1 . '-' . $key2;
                $new->name = 'name-' . $key1 . '-' . $key2;
                $new->status_id = 1;
                $new->author_id = 1;
                $new->stock_brand_id = $brand->id;
                $new->stock_category_id = $category->id;
                $new->avatar_id = null;
                $new->unit_price = rand(100, 1000);
                $new->tags = 'tags-' . $key1 . '-' . $key2;
                $new->description = 'description-' . $key1 . '-' . $key2;
                $new->total_quantity = 100;
                $new->published = 1;
                $new->save();
            }
        }
    }
}
