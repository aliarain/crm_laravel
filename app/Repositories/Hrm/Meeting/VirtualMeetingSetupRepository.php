<?php

namespace App\Repositories\Hrm\Meeting;

use App\Models\Hrm\VirtualMeeting\MeetingSetup;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class VirtualMeetingSetupRepository.
 */
class VirtualMeetingSetupRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return MeetingSetup::class;
    }

    public function setup()
    {
        $data['virtualMeetings'] = $this->model()::get();
        $data['title']=_trans('virtual-meeting.Virtual Meeting Setup');
        return $data;
    }
}
