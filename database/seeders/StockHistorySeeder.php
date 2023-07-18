<?php

namespace Database\Seeders;

use App\Models\StockHistory;
use App\Models\ProductPurchase;
use Illuminate\Database\Seeder;

class StockHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // `company_id`, `stock_product_id`, `product_purchase_id`, `invoice_no`, `batch_no`, `expiry_date`, `quantity`, `product_unit_id`, `unit_price`, `total`, `discount_type`, `discount`, `grand_total`
        $product_purchases=ProductPurchase::all();
        foreach ($product_purchases as $purchase) {
            $new=new StockHistory();
            $new->company_id=1;
            $new->stock_product_id=1;
            $new->product_purchase_id=$purchase->id;
            $new->invoice_no=$purchase->invoice_no;
            $new->batch_no=$purchase->batch_no;
            $new->expiry_date=date('Y-m-d');
            $new->quantity=100;
            $new->product_unit_id=1;
            $new->purchase_price=rand(100, 1000);
            $new->selling_price=$new->purchase_price+70;
            $new->total=100;
            $new->discount_type='percentage';
            $new->discount=10;
            $new->grand_total=90;
            $new->save();
        }

        // $update_stock_product
    }
}
