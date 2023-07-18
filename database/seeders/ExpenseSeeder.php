<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company\Company;
use Illuminate\Database\Seeder;
use App\Models\Expenses\HrmExpense;
use Illuminate\Support\Facades\Log;
use App\Models\Expenses\PaymentMethod;
use App\Models\Expenses\IncomeExpenseCategory;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        try {

            $payment_methods = [
                'Cash',
                'Cheque',
                'Bank Transfer',
                'Credit Card',
                'Debit Card',
                'Paypal',
                'Stripe',
                'Payoneer',
                'Paytm',
                'Amazon Pay',
                'Google Pay',
                'Apple Pay',
                'Phone Pay',
                'Other'
            ];

            $companis = Company::get();
            foreach ($payment_methods as $payment_method) {
                foreach ($companis as $company) {
                    $s = new PaymentMethod();
                    $s->company_id = $company->id;
                    $s->name = $payment_method;
                    $s->save();
                }
            }
        } catch (\Throwable $th) {
            Log::error($th);
            echo $th->getMessage();
        }


        try {
            $categories = [
                'Advance Salary',
                'Employee Loan',
                'Advertising',
                'Bank Charges',
                'Business Development',
                'Business Travel',
                'Communication',
                'Customer Service',
                'Delivery',
                'Events',
                'Food & Beverage',
                'Gifts',
                'Hospitality',
                'Human Resources',
                'Maintenance',
                'Marketing',
                'Meeting',
                'Office Supplies',
                'Purchasing',
                'Public Relations',
                'Food',
                'Others',
            ];
            foreach ($categories as $category) {
                foreach ($companis as $company) {
                    $s = new IncomeExpenseCategory();
                    $s->company_id = $company->id;
                    $s->name = $category;
                    $s->save();
                }
            }

            $income_category = [
                'Project',
                'Income',
                'Revenue',
                'COGS',
            ];
            foreach ($income_category as $category) {
                foreach ($companis as $company) {
                    $s = new IncomeExpenseCategory();
                    $s->company_id = $company->id;
                    $s->is_income  = 1;
                    $s->name = $category;
                    $s->save();
                }
            }
        } catch (\Throwable $th) {
            Log::error($th);
        }


       try {
           // sample data for expense table
           $users = User::all();
           foreach ($users as $user) {
               $expense_categories = IncomeExpenseCategory::where('company_id', $user->company_id)->get();
               foreach ($expense_categories as $expense_category) {
                   $hrm_expense = new HrmExpense();
                   $hrm_expense->company_id = $user->company_id;
                   $hrm_expense->user_id = $user->id;
                   $hrm_expense->income_expense_category_id = $expense_category->id;
                   $hrm_expense->date = date('Y-m-d');
                   $hrm_expense->remarks = 'remarks';
                   $hrm_expense->amount = 100 + rand(1, 100);
                   $hrm_expense->is_claimed_status_id = 11;
                   $hrm_expense->claimed_approved_status_id = 9;
                   $hrm_expense->save();
               }
           }
       } catch (\Throwable $th) {
           Log::error($th);
       }

    }
}
