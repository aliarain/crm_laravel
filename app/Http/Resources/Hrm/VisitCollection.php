<?php

namespace App\Http\Resources\Hrm;

use App\Models\Visit\Visit;
use App\Helpers\CoreApp\Traits\DateHandler;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VisitCollection extends ResourceCollection
{

    use DateHandler;

    public function toArray($request)
    {

        $visit = Visit::with('visitImages', 'notes', 'schedules')->where('id', $this->collection->first()->id)->first();
       
        return [
            'id' => $visit->id,
            'title' => $visit->title,
            'date' => $this->dateFormatWithoutTime($visit->date),
            'description' => $visit->description,
            'status' => ucfirst($visit->status),
            'status_color' => visitStatusColor($visit->status),
            'images' => $visit->visitImages->map(function ($image) {
                return [
                    'id' => $image->id,
                    'file_id' => $image->file_id,
                    'file_path' => uploaded_asset($image->file_id),
                ];
            }),
            'notes' => $visit->notes->map(function ($note) {
                return [
                    'note' => $note->note,
                    'status' => ucfirst($note->status),
                    'status_color' => visitStatusColor($note->status),
                    'date_time' => $note->created_at->format('jS M, Y'),
                ];
            }),
            'schedules' => $visit->schedules->map(function ($schedule) {
                return [
                    'title' => $schedule->title,
                    'latitude' => $schedule->latitude,
                    'longitude' => $schedule->longitude,
                    'note' => $schedule->note,
                    'status' => ucfirst($schedule->status),
                    'status_color' => visitStatusColor($schedule->status),
                    'date_time' => $schedule->created_at->format('jS M, Y H:i:s A'),
                ];
            }),
            'next_status' => [
                'status' => $visit->getNextStatus($visit)['status'],
                'status_text' => $visit->getNextStatus($visit)['status_text'],
            ],
        ];
    }

    public function with($request)
    {
        return [
            'env' => env('FILESYSTEM_DRIVER'),
            'result' => true,
            'message' => "Vist Details",
            'status' => 200
        ];
    }
}
