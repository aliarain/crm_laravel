<?php

namespace App\Http\Resources\Hrm;

use Carbon\Carbon;
use App\Models\Visit\Visit;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\TimeDurationTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MeetingCollection extends ResourceCollection
{
    use DateHandler,TimeDurationTrait;


    public function toArray($request)
    {

        return [
            'items' => $this->collection->map(function ($meeting) {
                return [
                    'id' => $meeting->id,
                    'title' => $meeting->title,
                    'date' => Carbon::parse($meeting->date)->format('F j'),
                    'day' => Carbon::parse($meeting->date)->format('l'),
                    'time' => $this->dateTimeInAmPm($meeting->start_at),
                    'start_at' => $this->timeFormatInPlainText($meeting->start_at),
                    'end_at' => $this->timeFormatInPlainText($meeting->end_at),
                    'location' => $meeting->location,
                    'duration' => $meeting->duration,
                    'participants' => $meeting->meetingParticipants->map(function ($participant) {
                        return [
                            'name' => $participant->participant->name,
                            'is_agree' => $participant->is_agree==1 ? 'Agree' : 'Disagree',
                            'is_present' => $participant->is_present==1 ? 'Present' : 'Absent',
                            'present_at' => $participant->present_at,
                            'started_at' => $participant->meeting_started_at,
                            'ended_at' => $participant->meeting_ended_at,

                        ];
                    }),
                ];
            })
        ];
    }
}
