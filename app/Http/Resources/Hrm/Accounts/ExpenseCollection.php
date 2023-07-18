<?php

namespace App\Http\Resources\Hrm\Accounts;

use Carbon\Carbon;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\TimeDurationTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExpenseCollection extends ResourceCollection
{
    use DateHandler,TimeDurationTrait;


    public function toArray($request)
    {

        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'category' => $data->category->name,
                    'requested_amount' => currency_format($data->request_amount),
                    'approved_amount' => currency_format($data->amount),
                    'date_show' => showDate($data->created_at->format('d-m-Y')),
                    'date_db' => $data->created_at->format('d-m-Y'),
                    'payment' => plain_text($data->payment->name),
                    'payment_color' => '0xFF'.@$data->payment->color_code,
                    'status' => plain_text($data->status->name),
                    'status_color' => '0xFF'.@$data->status->color_code,
                    'reason' => @$data->remarks,

                ];
            }),
        ];
    }

    public function with($request)
    {
        return [
            // 'env' => env('FILESYSTEM_DRIVER'),
            'result' => true,
            'message' => "Expense List",
            'payment_type' => [
                [
                    'id' => 8,
                    'name' => 'Paid',
                ],
                [
                    'id' => 9,
                    'name' => 'Unpaid',
                ]
            ],
            'expanse_status' => [
                [
                    'id' => 2,
                    'name' => 'Pending',
                ],
                [
                    'id' => 5,
                    'name' => 'Approved',
                ],
                [
                    'id' => 6,
                    'name' => 'Rejected',
                ],
            ],
            'status' => 200
        ];
    }
}
