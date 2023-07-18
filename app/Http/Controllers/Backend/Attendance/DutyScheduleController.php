<?php

namespace App\Http\Controllers\Backend\Attendance;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\HRM\Attendance\DutySchedule;
use App\Repositories\DutyScheduleRepository;
use App\Helpers\CoreApp\Traits\AuthorInfoTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class DutyScheduleController extends Controller
{
    use RelationshipTrait, AuthorInfoTrait, ApiReturnFormatTrait;

    protected $dutyScheduleRepository;
    protected $model;

    public function __construct(DutyScheduleRepository $dutyScheduleRepository, DutySchedule $dutySchedule)
    {
        $this->dutyScheduleRepository = $dutyScheduleRepository;
        $this->model = $dutySchedule;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->dutyScheduleRepository->table($request);
        }
        $data['table']    = route('dutySchedule.index');
        $data['url_id']    = 'duty_schedule_table_url';
        $data['class']     = 'table_class';
        $data['delete_url'] =  route('dutySchedule.deleteData');
        $data['checkbox'] = true;
        $data['fields'] = $this->dutyScheduleRepository->fields();

        $data['title'] = _trans('common.Duty Schedule');

        return view('backend.attendance.duty_schedule.index', compact('data'));
    }

    public function create()
    {
        $data = $this->dutyScheduleRepository->create();
        return view('backend.attendance.duty_schedule.create', compact('data'));
    }

    public function store(Request $request)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        $this->validate($request, [
            'shift_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'consider_time' => 'numeric',
            'status_id' => 'required'
        ]);

        try {
            $store = $this->dutyScheduleRepository->store($request);
            if ($store) {
                Toastr::success(_trans('response.Operation successful'), 'Success');
                return redirect()->route('dutySchedule.index');
            } else {
                Toastr::error(_trans('response.Something went wrong'), 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {

            Toastr::error(_trans('response.Something went wrong'), 'Error');
            return redirect()->back();
        }
    }

    public function show($duty_schedule)
    {
        $data = $this->dutyScheduleRepository->show($duty_schedule);
        return view('backend.attendance.duty_schedule.edit', compact('data'));
    }

    public function update(Request $request, DutySchedule $schedule)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        $this->validate($request, [
            'shift_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'consider_time' => 'numeric',
            'status_id' => 'required'
        ]);
        try {
            if ($this->isExistsWhenUpdate($schedule, $this->model, 'shift_id', $request->shift_id)) {
                $request['company_id'] = $this->companyInformation()->id;
                $request['duty_schedule_id'] = $schedule->id;

                $st = explode(':', $request->start_time);
                $ed = explode(':', $request->end_time);
                if (sizeof($st) > 2) {
                    $start_time = date('Y-m-d') . ' ' . $request->start_time;
                } else {
                    $start_time = date('Y-m-d') . ' ' . $request->start_time . ':00';
                }
                if ($request->end_on_same_date == 1) {
                    $end_date = date('Y-m-d');
                } else {
                    $end_date = date('Y-m-d', strtotime('+1 day'));
                }
                if (sizeof($ed) > 2) {
                    $end_time =  $end_date . ' ' . $request->end_time;
                } else {
                    $end_time =  $end_date . ' ' . $request->end_time . ':00';
                }
                $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $start_time);
                $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $end_time);

                $diff_in_minutes = $to->diffInMinutes($from);
                $request['hour'] = $diff_in_minutes / 60;
                $store = $this->dutyScheduleRepository->update($request);
                Toastr::success(_trans('response.Operation successful'), 'Success');
                return redirect()->route('dutySchedule.index');
            } else {
                Toastr::error("Already exists", 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong'), 'Error');
            return redirect()->back();
        }
    }

    public function delete($duty_schedule)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        try {
            $this->dutyScheduleRepository->distroy($duty_schedule);
            Toastr::success( _trans('common.Duty schedule has been deleted'), 'Success');
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error( _trans('common.Something went wrong'), 'Error');
            return redirect()->back();
        }
    }


    public function dutyScheduleDataTable(Request $request)
    {
        return $this->dutyScheduleRepository->dutyScheduleDataTable($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->dutyScheduleRepository->destroyAll($request);
    }
}
