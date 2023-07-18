<?php

namespace App\Http\Resources\Hrm;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Helpers\CoreApp\Traits\DateHandler;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DailyLeaveCollection extends ResourceCollection
{ 
    use DateHandler;
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
                    'id' => $data->id,    
                    'date' => $this->dateFormatWithoutTime($data->date),
                    'staff' => @$data->user->name, 
                    'avater' => @uploaded_asset($data->user->avatar_id),
                    'designation' => @$data->user->designation->title,
                    'leave_type' => str_replace('_', ' ',Str::title(@$data->leave_type)),
                    'time' => $this->timeFormatInPlainText($data->time),
                    'reason' => @$data->reason,
                    'approval_details'=> [
                        'manager_approval' => $data->approved_at_tl!=""? @$data->tlApprovedBy->name .' Approved at '. @$this->dateFormatInPlainText($data->approved_at_tl):null,
                        'hr_approval' => $data->approved_at_hr!=""? @$data->hrApprovedBy->name .' Approved at '. @$this->dateFormatInPlainText($data->approved_at_hr):null,
                    ],
                    'tl_approval_msg' => $data->approved_at_tl!=""? 'TL Has Been Approved at '. @$this->dateFormatInPlainText($data->approved_at_tl):'TL Has Not Been Approved Yet',
                    'status'=>  ($data->approved_at_tl !=""  ||  $data->approved_at_hr != "")  ? 'Approved' : 'Pending',
                ];
            }),
        ];
    }
}