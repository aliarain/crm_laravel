<?php

namespace App\Http\Resources\Hrm;

use Carbon\Carbon;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\CurrencyTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExpenseClaimCollection extends ResourceCollection
{
    use CurrencyTrait;

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'date' => $data->created_at->format('d-m-Y'),
                    'category' => @$data->hrmExpense->expenseCategory->name,
                    'amount' => $this->getCurrency()."{$data->amount}",
                ];
            }),
        ];
    }
}
