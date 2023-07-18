<?php

namespace App\Http\Resources\Hrm\Attendance;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserAttendanceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'users' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'phone' => $data->phone,
                    'designation' => @$data->designation->title,
                    'avatar' => uploaded_asset($data->avatar_id),
                    'appreciates' => $data->appreciates->map(function ($appreciate) {
                        return [
                            'appreciate_by' => $appreciate->appreciateFrom->name,
                            'message' => $appreciate->message,
                            'date' => onlyDateMonthYear($appreciate->created_at),
                        ];
                    }),
                ];
            }),
        ];
    }
}
