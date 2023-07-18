<?php

namespace App\Http\Controllers\Backend\Attendance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\HRM\Attendance\Weekend;
use App\Repositories\WeekendsRepository;
use App\Http\Requests\Setting\WeekendRequest;

class WeekendsController extends Controller
{
    protected $weekend;

    public function __construct(WeekendsRepository $weekendsRepository)
    {
        $this->weekend = $weekendsRepository;
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $this->weekend->table($request);
            }
            $data['table']    = route('weekendSetup.index');
            $data['url_id']    = 'weekend_payment_table_url';
            $data['class']     = 'table_class';

            $data['fields'] = $this->weekend->fields();

            $data['title'] = _trans('leave.Weekend list');
            $data['weekends'] = $this->weekend->index();
            return view('backend.attendance.weekend.index', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function show(Weekend $weekend)
    {
        try {
            $data['title']     = _trans('common.Update Weekend');
            $data['url']       = route('weekendSetup.update',$weekend->id);
            $data['attributes'] = $this->weekend->editAttributes($weekend);
            @$data['button']   = _trans('common.Save');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function update(WeekendRequest $request, $id)
    {
        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->weekend->newUpdate($request, $id);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
