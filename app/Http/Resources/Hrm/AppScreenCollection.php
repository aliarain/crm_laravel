<?php

namespace App\Http\Resources\Hrm;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AppScreenCollection extends ResourceCollection
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
            'data' => $this->collection->map(function ($data) {
                return [
                    'name' => $data->name,
                    'slug' => $data->slug,
                    'position' => $data->position,
                    'icon' => my_asset($data->icon)
                ];
            })
        ];
    }

}
