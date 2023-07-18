<?php

namespace App\Http\Resources\Hrm;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AssignedLeavesCollection extends ResourceCollection
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
            'assignedLeaves' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'type' => $data->type->name
                ];
            })
        ];
    }
}
