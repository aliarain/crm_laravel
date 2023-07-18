<?php

namespace Database\Seeders;

use App\Models\StockProduct;
use Illuminate\Database\Seeder;
use App\Models\StockPaymentHistory;

class StockPaymentHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // `company_id`, `type`, `amount`, `reference`, `payment_type`, `bank_id`, `payment_method_id`, `bank_reference`,
        // `bank_name`, `bank_account`, `bank_branch`, `bank_account_holder`, `cheque_number`, `cheque_date`, `cheque_bank`, 
        //`cheque_branch`, `cheque_account_holder`, `email`, `transaction_id`, `transaction_date`
        $products=StockProduct::all();
        foreach ($products as $product) {
            $new=new StockPaymentHistory();
            $new->company_id=1;
            $new->type='purchase';
            $new->amount=100;
            $new->reference='reference';
            $new->payment_type='cash';
            $new->transaction_date=date('Y-m-d');
            $new->save();
        }
        $products=StockProduct::all();
        foreach ($products as $product) {
            $new=new StockPaymentHistory();
            $new->company_id=1;
            $new->type='sale';
            $new->amount=100;
            $new->reference='reference';
            $new->payment_type='cash';
            $new->transaction_date=date('Y-m-d');
            $new->save();
        }
    }
}
