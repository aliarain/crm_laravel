<?php

namespace App\Http\Resources\Hrm;

use App\Helpers\CoreApp\Traits\CurrencyTrait;
use App\Helpers\CoreApp\Traits\DateHandler;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClaimHistoryCollection extends ResourceCollection
{
    use DateHandler,CurrencyTrait;

    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'invoice_number' => $data->invoice_number,
                    'claim_date' => $data->claim_date,
                    'remarks' => $data->remarks,
                    'payable_amount' => $this->getCurrency().$data->payable_amount,
                    'due_amount' => $this->getCurrency().$data->due_amount,
                    'attachment_file_id' => uploaded_asset($data->attachment_file_id),
                    'status_id' => @$data->status->name
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
