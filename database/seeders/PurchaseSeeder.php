<?php

namespace Database\Seeders;

use App\Models\ProductPurchase;
use Illuminate\Database\Seeder;
use App\Models\StockPaymentHistory;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $history=StockPaymentHistory::where('type','purchase')->get();

        foreach ($history as $key => $value) {
            $new=new ProductPurchase();
            $new->company_id=1;
            $new->stock_payment_history_id=$value->id;
            $new->client_id=1;
            $new->invoice_no='invoice_no-'.$key;
            $new->date=date('Y-m-d');
            $new->batch_no='batch_no-'.$key;
            $new->total=100;
            $new->tax=10;
            $new->grand_total=110;
            $new->save();
        }
    }
}
