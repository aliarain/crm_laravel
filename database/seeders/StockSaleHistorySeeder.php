<?php

namespace Database\Seeders;

use App\Models\StockSale;
use Illuminate\Database\Seeder;
use App\Models\StockSaleHistory;

class StockSaleHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //`company_id`, `stock_sale_id`, `stock_product_id`, `quantity`, `price`, `discount`, `tax`, `total`
        $history=StockSale::all();
        foreach ($history as $sale) {
            $new=new StockSaleHistory();
            $new->company_id=1;
            $new->stock_sale_id=$sale->id; 
            $new->stock_product_id=$sale->stock_product_id;
            $new->quantity=100;
            $new->price=100;
            $new->discount=10;
            $new->tax=10;
            $new->total=90;
            $new->save();
        }
    }
}
