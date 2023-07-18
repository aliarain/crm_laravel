<?php

namespace Database\Seeders;

use App\Models\StockBrand;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class StockBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $brands=[
            'Acer',
            'Apple',
            'Asus',
            'Bajaj',
            'Bosch',
            'Canon',
            'Dell',
            'Epson',
            'Fujifilm',
            'Godrej',
            'Haier',
            'Burger King',
            'KFC',
            'McDonalds',
            'Pizza Hut',
            'Subway',
            'Wendys',
            'Aarogyam',
            'Apollo',
            'Apollo Pharmacy',
            'Arogya',
            'Arogya Pharmacy',
            'Adidas',
            'Allen Solly',
            'Arrow',
            'Biba',
            'Biba Kids',

        ];

        foreach ($brands as $brand) {
            StockBrand::create([
                'name' => $brand,
                'slug' => Str::slug($brand),
                'status_id' => 1,
                'author_info_id' => 1,
                'avatar_id' => null,
            ]);
        }

    }
}
