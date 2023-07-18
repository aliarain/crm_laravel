<?php

namespace App\Http\Controllers\Backend\VirtualMeeting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Hrm\Meeting\VirtualMeetingRepository;
use App\Repositories\Hrm\Meeting\VirtualMeetingSetupRepository;

class VirtualMeetingController extends Controller
{
    protected $virtualMeetingRepo;
    protected $SetupRepo;


    public function __construct()
    {
        $this->virtualMeetingRepo = new VirtualMeetingRepository();
        $this->SetupRepo = new VirtualMeetingSetupRepository();
    }

    public function index()
    {
        $virtualMeetings = $this->virtualMeetingRepo->get();
        return back();
    }

    public function setup()
    {
        $data['setup'] = $this->SetupRepo->setup();
        return $data;
        return view('backend.virtual-meeting.setup', $data);
    }
}
