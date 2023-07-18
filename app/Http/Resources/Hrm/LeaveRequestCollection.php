<?php

namespace App\Http\Resources\Hrm;

use App\Helpers\CoreApp\Traits\DateHandler;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LeaveRequestCollection extends ResourceCollection
{
    use DateHandler;

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'leaveRequests' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'type' => $data->assignLeave->type->name,
                    'days' => $data->days,
                    'apply_date' => $this->getMonthDate($data->apply_date),
                    'status' => @$data->status->name,
                    'color_code' => appColorCodePrefix() . @$data->status->color_code
                ];
            })
        ];
    }
}
