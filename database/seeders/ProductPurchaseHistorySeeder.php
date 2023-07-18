<?php

namespace Database\Seeders;

use App\Models\ProductPurchase;
use Illuminate\Database\Seeder;
use App\Models\ProductPurchaseHistory;

class ProductPurchaseHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $history=ProductPurchase::all();
        foreach ($history as $purchase) {
            $new=new ProductPurchaseHistory();
            $new->company_id=1;
            $new->product_purchase_id=$purchase->id;
            $new->batch_no='batch_no-'.$purchase->id;
            $new->product_unit_id=1;
            $new->quantity=100;
            $new->price=100;
            $new->discount=10;
            $new->total=90;
            $new->save();
        }
    }
}
