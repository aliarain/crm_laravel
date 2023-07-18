<?php

namespace App\Http\Controllers\Backend\Expense;

use App\Http\Controllers\Controller;
use App\Models\Expenses\IncomeExpenseCategory;
use App\Repositories\Hrm\Expense\ExpenseCategoryRepository;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    protected $category;

    public function __construct(ExpenseCategoryRepository $expenseCategoryRepository)
    {
        $this->category = $expenseCategoryRepository;
    }

    public function getExpenseCategory(): \Illuminate\Http\JsonResponse
    {
        return $this->category->getExpenseCategory();
    }

}
