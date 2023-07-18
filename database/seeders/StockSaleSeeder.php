<?php

namespace Database\Seeders;

use App\Models\StockSale;
use Illuminate\Database\Seeder;
use App\Models\StockPaymentHistory;

class StockSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // `company_id`, `client_id`, `stock_product_id`, `stock_payment_history_id`, `invoice`, `date`, `status_id`, `payment_status_id`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `price`, `discount`, `tax`, `total`, 
        $history=StockPaymentHistory::where('type','sale')->get();
        foreach($history as $history)
        {
            $new=new StockSale();
            $new->company_id=1;
            $new->client_id=1;
            $new->stock_product_id=1;
            $new->stock_payment_history_id=$history->id;
            $new->invoice='invoice-'.$history->id;
            $new->date=date('Y-m-d');
            $new->status_id=1;
            $new->payment_status_id=1;
            $new->created_by=1;
            $new->updated_by=1;
            $new->deleted_by=1;
            $new->deleted_at=date('Y-m-d');
            $new->price=100;
            $new->discount=10;
            $new->tax=10;
            $new->total=90;
            $new->save();
        }
    }
}
