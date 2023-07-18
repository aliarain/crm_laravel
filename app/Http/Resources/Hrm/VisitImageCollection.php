<?php

namespace App\Http\Resources\Hrm;

use App\Models\Visit\Visit;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VisitImageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
   
    public function toArray($request)
    {
        $visit = Visit::with('visitImages','notes','schedules')->where('id',$this->collection->first()->id)->first();
        return [
                'images'=>$visit->visitImages->map(function($image){
                    return [
                        'id'=>$image->id,
                        'file_id'=>$image->file_id,
                        'file_path'=>uploaded_asset($image->file_id),
                    ];
                }),
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
