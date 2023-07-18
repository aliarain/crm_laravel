<?php

namespace App\Http\Resources\Hrm;

use Carbon\Carbon;
use App\Models\Visit\Visit;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\TimeDurationTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HolidayCollection extends ResourceCollection
{
    use DateHandler,TimeDurationTrait;


    public function toArray($request)
    {

        return [
            'items' => $this->collection->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'date' => Carbon::parse($event->start_date)->format('F j'),
                    'day' => Carbon::parse($event->start_date)->format('l'),
                    'start_date' => $this->dateFormatWithoutTime($event->start_date),
                    'attachment_file_id' => uploaded_asset($event->attachment_id),
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'env' => env('FILESYSTEM_DRIVER'),
            'result' => true,
            'message' => "Event List",
            'status' => 200
        ];
    }
}
