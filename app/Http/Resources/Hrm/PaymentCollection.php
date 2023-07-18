<?php

namespace App\Http\Resources\Hrm;

use App\Helpers\CoreApp\Traits\CurrencyTrait;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentCollection extends ResourceCollection
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
                    'invoice_number' => $data->code,
                    'payment_date' => Carbon::parse($data->payment_date)->format('d/m/Y'),
                    'payable_amount' => $this->getCurrency().$data->payable_amount,
                    'paid_amount' => $this->getCurrency().intval($data->paid_amount),
                    'due_amount' => $this->getCurrency().intval($data->due_amount),
                    'status' => @$data->paymentStatus->name,
                    'color_code' => appColorCodePrefix().@$data->paymentStatus->color_code,
                ];
            }),
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
        ];
    }

}
