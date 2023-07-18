<?php

namespace Database\Seeders;

use App\Models\StockCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StockCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $categories = [
            'Food',
            'Beverage',
            'Grocery',
            'Vegetable',
            'Fruit',
            'Meat',
            'Fish',
            'Dairy',
            'Bakery',
            'Confectionery',
            'Mobile',
            'Laptop',
            'Tablet',
            'Camera',
            'Television',
            'Computer',
            'Printer',
            'Scanner',
            'Projector',
            'Speaker',
            'Headphone',
            'Medicine',
            'Vitamin',
            'Supplement',
            'Herbal',
            'Cosmetic',
            'Beauty',
            'Skin Care',
            'Hair Care',
            'Oral Care',
        ];
        foreach ($categories as $category) {
            StockCategory::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'status_id' => 1,
                'author_info_id' => 1,
                'avatar_id' => null,
            ]);
        }

    }
}
