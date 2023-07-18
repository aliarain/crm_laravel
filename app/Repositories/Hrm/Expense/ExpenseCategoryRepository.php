<?php

namespace App\Repositories\Hrm\Expense;

use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Models\Expenses\IncomeExpenseCategory;

class ExpenseCategoryRepository
{
    use RelationshipTrait, ApiReturnFormatTrait;

    protected $model;

    public function __construct(IncomeExpenseCategory $category)
    {
        $this->model = $category;
    }

    public function getExpenseCategory()
    {
        $data = $this->model->query()->where(['company_id' => $this->companyInformation()->id, 'status_id'=> 1])->select('id','name')->get();
        return $this->responseWithSuccess('Expense categories', $data, 200);
    }

    public function model($filter = null)
    {
        $model = $this->model->toBase();
        if ($filter) {
            $model = $this->model->where($filter);
        }
        return $model;
    }

}