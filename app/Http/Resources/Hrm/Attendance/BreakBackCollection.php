<?php

namespace App\Http\Resources\Hrm\Attendance;

use App\Helpers\CoreApp\Traits\TimeDurationTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BreakBackCollection extends ResourceCollection
{
    use TimeDurationTrait;

    public function toArray($request)
    {
        return [
            'today_history' => $this->collection->map(function ($data) {
                return [
                    'name' => @$data->user->name,
                    'reason' => $data->reason,
                    'break_time_duration' => $this->hourOrMinute($data->break_time,$data->back_time),
                    'break_back_time' => $this->dateTimeInAmPm($data->break_time,).' To '.$this->dateTimeInAmPm($data->back_time),
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