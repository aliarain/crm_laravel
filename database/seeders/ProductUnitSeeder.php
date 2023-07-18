<?php

namespace Database\Seeders;

use App\Models\ProductUnit;
use Illuminate\Database\Seeder;

class ProductUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productUnitsArray = [
            'Kg',
            'Gram',
            'Litre',
            'Millilitre',
            'Pcs',
            'Box',
            'Bottle',
            'Packet',
            'Dozen',
            'Carton',
            'Roll',
            'Meter',
            'Feet',
            'Inch',
            'Pound',
            'Ounce',
            'Other',
        ];
        foreach ($productUnitsArray as $productUnit) {
            ProductUnit::create([
                'company_id' => 1,
                'name' => $productUnit,
                'status_id' => 1,
            ]);
        }
    }
}
