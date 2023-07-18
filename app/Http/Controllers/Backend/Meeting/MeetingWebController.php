<?php

namespace App\Http\Controllers\Backend\Meeting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\Meeting\CreateMeetingRequest;
use App\Repositories\Hrm\Meeting\MeetingRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Repositories\Hrm\Department\DepartmentRepository;

class MeetingWebController extends Controller
{
    use ApiReturnFormatTrait;

    protected $meeting;
    protected $departmentRepository;

    public function __construct(MeetingRepository $meetingRepository, DepartmentRepository $departmentRepository)
    {
        $this->meeting = $meetingRepository;
        $this->departmentRepository = $departmentRepository;
    }

    public function index()
    {
        try {
            $data['checkbox'] = true;
            $data['title']     = _trans('Meeting.Meeting List');
            $data['table']     = route('meeting.table');
            $data['url_id']    = 'meeting_table_url';
            $data['fields']    = $this->meeting->fields();
            $data['class']     = 'meeting_table_class';
            $data['departments'] = $this->departmentRepository->getAll();
            $data['delete_url'] =  route('dutySchedule.deleteData');
            $data['status_url'] =  route('dutySchedule.deleteData');
            
            return view('backend.meeting.index', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function table(Request $request)
    {
        if ($request->ajax()) {
            return $this->meeting->table($request);
        }
    }

    public function create()
    {
    
        try {
            $data['title']         = _trans('meeting.Create Meeting');
            $data['url']       = route('meeting.store');
            $data['attributes'] = $this->meeting->createAttributes();
            @$data['button']   = _trans('common.Save');
            return view('backend.form.create', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }



    public function store(CreateMeetingRequest $request)
    {
        
        try {
            if (demoCheck()) {
                Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
                return redirect()->back();
            }
            $result = $this->meeting->newStore($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('meeting.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $data['title']         = _trans('meeting.Create Meeting');
            $data['url']       = route('meeting.update', $id);
            $data['attributes'] = $this->meeting->editAttributes($this->meeting->getModel()->find($id));
            @$data['button']   = _trans('common.Save');
            return view('backend.form.create', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }


    public function update(CreateMeetingRequest $request, $id)
    {
        try {
            if (demoCheck()) {
                Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
                return redirect()->back();
            }
            $result = $this->meeting->newUpdate($request, $id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('meeting.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }
    public function view($id)
    {
        try {
            $data['view']       = $this->meeting->getModel([
                'id' => $id,
                'company_id' =>  auth()->user()->company_id
            ])->first();
            if (!blank($data['view'])) {
                $data['title']    = _trans('common.Meeting View');
                return view('backend.meeting.view', compact('data'));
            } else {
                Toastr::error(_trans('message.Data not found!'));
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->meeting->delete($id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('meeting.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    // status change
    public function statusUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        return $this->meeting->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->meeting->destroyAll($request);
    }
}
