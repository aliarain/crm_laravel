<?php

namespace App\Http\Resources\Hrm;

use App\Helpers\CoreApp\Traits\TimeDurationTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AttendanceCollection extends ResourceCollection
{
    use TimeDurationTrait;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'title' => $data->title,
                    'message' => $data->message,
                    'image' => uploaded_asset($data->image_id),
                    'date' => Carbon::parse($data->created_at)->format('d F Y h:i:s A'),
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
