<?php

namespace App\Http\Resources\Hrm;

use App\Helpers\CoreApp\Traits\CurrencyTrait;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExpenseCollection extends ResourceCollection
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
                    'category' => $data->expenseCategory->name,
                    'remarks' => $data->remarks,
                    'amount' => $this->getCurrency().$data->amount,
                ];
            }),
            'links' => [
                "first" => \request()->url()."?page=1",
                "last" => \request()->url()."?page=1",
                "prev" => null,
                "next" => null
            ],
            'pagination' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage()
            ],
        ];
    }
}
