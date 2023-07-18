<?php

namespace App\Http\Controllers\Backend\Sms;

use App\Models\SmsLog;
use Illuminate\Http\Request;
use App\Models\Hrm\Notice\Notice;
use App\Http\Controllers\Controller;
use App\Http\Requests\NoticeReqeust;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\SendSmsReqeust;
use App\Models\Permission\Permission;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Repositories\Hrm\Sms\SmsRepository;
use App\Repositories\Company\CompanyRepository;
use App\Repositories\Hrm\Notice\NoticeRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Department\DepartmentRepository;


class SmsController extends Controller
{
    use ApiReturnFormatTrait, RelationshipTrait, FileHandler;
    protected $notice;
    protected $department;
    protected $company;
    protected $model;
    public function __construct(SmsRepository $smsRepository, DepartmentRepository $department, CompanyRepository $company, SmsLog $smsLog)
    {
        $this->sms = $smsRepository;
        $this->department = $department;
        $this->company = $company;
        $this->model = $smsLog;
    }

    public function listView(Request $request)
    {
        return $this->notice->noticeList($request);
    }
    public function index(Request $request)
    {
        $data['title'] = _trans('common.Sms List');
        return view('backend.send_sms.index', compact('data'));
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        return $this->notice->show($id);
    }

    public function clear(): \Illuminate\Http\JsonResponse
    {
        return $this->notice->clearNotice();
    }

    public function edit(Notice $notice)
    {
        $data['title'] = _trans('common.Edit notice');
        $data['notice'] = $notice;
        $data['departments'] = $this->department->getAll();
        $data['companies'] =  $this->company->getAll();
        return view('backend.send_sms.edit', compact('data'));
    }

    public function create()
    {
        $data['title'] = _trans('common.Send SMS');
        $data['departments'] = $this->department->getAll();
        $data['permissions'] = Permission::get();
        return view('backend.send_sms.create', compact('data'));
    }

    public function store(SendSmsReqeust $request): \Illuminate\Http\RedirectResponse
    {

        try {
            $this->sms->store($request);
            Toastr::success(_trans('response.Operation successful'), 'Success');
            return redirect()->route('sms.index');
        } catch (\Exception $exception) {
            Toastr::error(_trans('response.Something went wrong'), 'Error');
            return redirect()->back();
        }
    }

    public function update(NoticeReqeust $request, Notice $notice): \Illuminate\Http\RedirectResponse
    {

        try {
            if ($this->isExistsWhenUpdate($notice, $this->model, 'subject', $request->subject)) {
                $request['company_id'] = $this->companyInformation()->id;
                $request['notice_id'] = $notice->id;
                $this->notice->update($request);
                Toastr::success(_trans('response.Operation successful'), 'Success');
                return redirect()->route('notice.index');
            } else {
                Toastr::error("{$request->subject} already exists", 'Error');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function dataTable(Request $request)
    {
        return $this->notice->noticeDatatable($request);
    }

    public function delete(Notice $notice): \Illuminate\Http\RedirectResponse
    {

        try {
            $this->notice->destroy($notice);
            Toastr::success(_trans('response.Operation successful'), 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
}
