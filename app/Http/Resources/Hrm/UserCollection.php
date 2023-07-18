<?php

namespace App\Http\Resources\Hrm;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
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
            'members' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'phone' => $data->phone,
                    'email' => $data->email,
                    'designation' => @$data->designation->title,
                    'department' => @$data->department->title,
                    'avatar' => uploaded_asset($data->avatar_id),
                ];
            }),
        ];
    }
}
