<?php

namespace App\Models\coreApp\Relationship;

use Illuminate\Support\Facades\DB;

trait RelationshipTrait
{
    public function companyInformation(): object
    {
        return auth()->user()->company;
    }

    public function isExistsWhenStore($model, $columnName, $value): bool
    {
        $data = $model->query()->where([
            'company_id' => $this->companyInformation()->id,
            $columnName => $value
        ])->first();
        if (!$data) {
            return true;
        } else {
            return false;
        }
    }


    public function isExistsWhenUpdate($existingModel, $model, $columnName, $value): bool
    {
        $data = $model->query()->where([
            'company_id' => $this->companyInformation()->id,
            $columnName => $value
        ])->first();
        if ($data) {
            if ($existingModel->id == $data->id) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }


    public function isExistsWhenStoreMultipleColumn($model, $column1, $column2, $value1, $value2): bool
    {
        $data = $model->query()->where([
            'company_id' => $this->companyInformation()->id,
            $column1 => $value1,
            $column2 => $value2
        ])->first();
        if (!$data) {
            return true;
        } else {
            return false;
        }
    }
}