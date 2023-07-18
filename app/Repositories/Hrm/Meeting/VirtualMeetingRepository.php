<?php

namespace App\Repositories\Hrm\Meeting;

use App\Models\Hrm\VirtualMeeting\VirtualMeeting;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class VirtualMeetingRepository.
 */
class VirtualMeetingRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return VirtualMeeting::class;
    }

    
}
