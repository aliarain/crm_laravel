<?php

namespace App\Http\Resources\Hrm;

use App\Models\Visit\Visit;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VisitListCollection extends ResourceCollection
{

    public function toArray($request)
    {

        return [
            'my_visits' => $this->collection->whereIn('status', ['created', 'started', 'reached'])->map(function ($visit) {
                return [
                    'id' => $visit->id,
                    'title' => $visit->title,
                    'date' => date('jS M, Y', strtotime($visit->date)),
                    'status' => ucfirst($visit->status),
                    'status_color' => visitStatusColor($visit->status),
                ];
            })->toArray(),
            
        ];
    }
    public function with($request)
    {
        return [
            'result' => true,
            'message' => "Vist List Loaded",
            'status' => 200
        ];
    }
}
