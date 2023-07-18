<?php

namespace Database\Seeders;

use App\Models\Finance\Deposit;
use App\Models\Finance\Expense;
use Illuminate\Database\Seeder;
use App\Models\Management\Client;
use App\Models\Finance\Transaction;
use App\Models\Expenses\IncomeExpenseCategory;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories=IncomeExpenseCategory::where('is_income',1)->take(4)->get();
        $income_source=[
            'Upwork',
            'Fiverr',
            'Freelancer',
            'Project',
            'Transfer',

        ];
        $clients=Client::get();
        foreach ($clients as $key => $client) {
            foreach ($categories as $key => $category) {
                foreach ($income_source as $key => $source) {
                    $transaction=new Transaction();
                    $transaction->company_id=1;
                    $transaction->account_id=1;
                    $transaction->client_id=$client->id;
        
                    $year=rand(2021,2022);
                    $month=rand(1,12);
                    $day=rand(1,28);
                    $transaction->date=date('Y-m-d',strtotime($year.'-'.$month.'-'.$day));
                    $transaction->description='Income from '.$source;
                    $transaction->amount=rand(1000,10000);
                    $transaction->transaction_type=19;
                    $transaction->status_id=rand(8,9);
                    $transaction->created_by=1;
                    $transaction->updated_by=1;
                    $transaction->company_id=rand(1,2);
                    $transaction->save();

                    $deposit=new Deposit();
                    $deposit->user_id=2;
                    $deposit->company_id=2;
                    $deposit->date=date('Y-m-d',strtotime($year.'-'.$month.'-'.$day));
                    $deposit->income_expense_category_id=$category->id;
                    $deposit->amount=$transaction->amount;
                    $deposit->request_amount=$transaction->amount;
                    $deposit->payment_method_id=$transaction->payment_method_id;
                    $deposit->transaction_id=$transaction->id;
                    $deposit->pay=$transaction->amount;
                    $deposit->status_id=$transaction->status_id;
                    $deposit->save();

                }
            }
        }
        $categories=IncomeExpenseCategory::where('is_income',0)->take(4)->get();
        $expense_source=[
            'Rent',
            'Electricity',
            'Internet',
            'Water',
            'Transfer',
        ];
            foreach ($categories as $key => $category) {
                foreach ($expense_source as $key => $source) {
                    $transaction=new Transaction();
                    $transaction->company_id=1;
                    $transaction->account_id=1;
                    $transaction->client_id=$client->id;
        
                    $year=rand(2021,2022);
                    $month=rand(1,12);
                    $day=rand(1,28);
                    $transaction->date=date('Y-m-d',strtotime($year.'-'.$month.'-'.$day));
                    $transaction->description='Expense from '.$source;
                    $transaction->amount=rand(1000,10000);
                    $transaction->transaction_type=18;
                    $transaction->status_id=rand(8,9);
                    $transaction->created_by=1;
                    $transaction->updated_by=1;
                    $transaction->company_id=rand(1,2);
                    $transaction->save();

                    $deposit=new Expense();
                    $deposit->user_id=2;
                    $deposit->company_id=2;
                    $deposit->date=date('Y-m-d',strtotime($year.'-'.$month.'-'.$day));
                    $deposit->income_expense_category_id=$category->id;
                    $deposit->amount=$transaction->amount;
                    $deposit->request_amount=$transaction->amount;
                    $deposit->payment_method_id=$transaction->payment_method_id;
                    $deposit->transaction_id=$transaction->id;
                    $deposit->pay=$transaction->amount;
                    $deposit->status_id=$transaction->status_id;
                    $deposit->save();
                }
            }
    }
}
