<?php

namespace App\Http\Resources\Hrm;

use Carbon\Carbon;
use App\Models\Visit\Visit;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\TimeDurationTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AppoinmentCollection extends ResourceCollection
{
    use DateHandler,TimeDurationTrait;


    public function toArray($request)
    {

        return [
            'items' => $this->collection->map(function ($appoinment) {
                return [
                    'id' => $appoinment->id,
                    'title' => $appoinment->title,
                    'date' => Carbon::parse($appoinment->date)->format('F j'),
                    'day' => Carbon::parse($appoinment->date)->format('l'),
                    'time' => $this->dateTimeInAmPm($appoinment->appoinment_start_at),
                    'start_at' => $this->timeFormatInPlainText($appoinment->appoinment_start_at),
                    'end_at' => $this->timeFormatInPlainText($appoinment->appoinment_end_at),
                    'location' => $appoinment->location,
                    'appoinmentWith' => @$appoinment->appoinmentWith->name,
                    'participants' => $appoinment->participants->map(function ($participant) {
                        return [
                            'name' => $participant->participantInfo->name,
                            'is_agree' => $participant->is_agree==1 ? 'Agree' : 'Disagree',
                            'is_present' => $participant->is_present==1 ? 'Present' : 'Absent',
                            'present_at' => $participant->present_at,
                            'appoinment_started_at' => $participant->appoinment_started_at,
                            'appoinment_ended_at' => $participant->appoinment_ended_at,
                            'appoinment_duration' => $participant->appoinment_duration,
                        ];
                    }),
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'env' => env('FILESYSTEM_DRIVER'),
            'result' => true,
            'message' => "Appointment List",
            'status' => 200
        ];
    }
}
