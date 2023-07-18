<?php

namespace App\Http\Resources\Hrm;

use App\Models\Visit\Visit;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VisitHistoryCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return [
            'history'=> $this->collection->map(function ($visit) {
                return [
                    'id' => $visit->id,
                    'title' => $visit->title,
                    'year' => date('Y', strtotime($visit->date)),
                    'month' => date('M', strtotime($visit->date)),
                    'day' => date('d', strtotime($visit->date)),
                    'started' =>onlyTimePlainText($visit->created_at),
                    'reached' =>$visit->status !='cancelled'?getReached($visit):'',
                    'duration' =>$visit->status !='cancelled'? getDurration($visit):'',
                    'status' => ucfirst($visit->status),
                    'status_color' => visitStatusColor($visit->status),
                ];
            })
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
