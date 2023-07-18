<?php

namespace App\Http\Resources\Hrm;

use App\Helpers\CoreApp\Traits\DateHandler;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class LeaveApprovalCollection extends ResourceCollection
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
                $day = Str::plural('day', $data->days);
                return [
                    'id' => $data->id,
                    'user_id' => $data->user_id,
                    'name' => $data->user->name,
                    'message' => "{$data->user->name} requested for {$data->days} {$day} {$data->assignLeave->type->name}",
                    'type' => $data->assignLeave->type->name,
                    'days' => $data->days,
                    'apply_date' => $this->getMonthDate($data->apply_date),
                    'date_duration' => $data->days > 1 ? "{$this->getMonthDate($data->leave_from)} - {$this->getMonthDate($data->leave_to)}" : $this->getMonthDate($data->leave_from),
                    'status' => @$data->status->name,
                    'color_code' => appColorCodePrefix() . @$data->status->color_code
                ];
            })
        ];
    }
}
