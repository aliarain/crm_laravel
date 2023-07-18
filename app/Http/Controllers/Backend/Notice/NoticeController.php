<?php

namespace App\Http\Controllers\Backend\Notice;

use Illuminate\Http\Request;
use App\Models\Hrm\Notice\Notice;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\NoticeReqeust;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Permission\Permission;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Models\Hrm\Notice\NoticeDepartment;
use App\Repositories\Company\CompanyRepository;
use App\Repositories\Hrm\Notice\NoticeRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Department\DepartmentRepository;

class NoticeController extends Controller
{

    use ApiReturnFormatTrait, RelationshipTrait, FileHandler;
    protected $notice;
    protected $department;
    protected $company;
    protected $model;
    public function __construct(NoticeRepository $noticeRepository, DepartmentRepository $department, CompanyRepository $company, Notice $notice)
    {
        $this->notice = $noticeRepository;
        $this->department = $department;
        $this->company = $company;
        $this->model = $notice;
    }

    public function listView(Request $request)
    {
        return $this->notice->noticeList($request);
    }
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $this->notice->table($request);
            }
            $data['departments'] = $this->department->getAll();
            $data['fields']        = $this->notice->fields();
            $data['checkbox']      = true;
            $data['delete_url']    = route('notice.delete_data');
            $data['url_id']        = 'notice_table_url';
            $data['class']         = 'table_class';
            $data['table']     = route('notice.index');

            $data['title'] = _trans('notice.Notice List');

            return view('backend.notice.index', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
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
        $data['title'] = _trans('notice.Edit notice');
        $data['notice'] = $notice;
        $data['departments'] = $this->department->getAll();
        $data['companies'] =  $this->company->getAll();
        return view('backend.notice.edit', compact('data'));
    }

    public function create()
    {
        $data['title'] = _trans('notice.Add Notice');
        $data['departments'] = $this->department->getAll();
        $data['companies'] =  $this->company->getAll();
        $data['permissions'] = Permission::get();
        return view('backend.notice.create', compact('data'));
    }

    public function storeNotice(NoticeReqeust $request)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        try {
            if ($this->isExistsWhenStore($this->model, 'subject', $request->subject)) {
                return  $this->notice->storeNotice($request);
            } else {
                return $this->responseWithError("{$request->subject} already exists", 400);
            }
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), 400);
        }
    }
    public function store(NoticeReqeust $request)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        try {
            if ($this->isExistsWhenStore($this->model, 'subject', $request->subject)) {
                $this->notice->store($request);
                Toastr::success(_trans('response.Operation successful'), 'Success');
                return redirect()->route('notice.index');
            } else {
                Toastr::error("{$request->subject} already exists", 'Error');
                return redirect()->back();
            }
        } catch (\Exception $exception) {
            Toastr::error(_trans('response.Something went wrong'), 'Error');
            return redirect()->back();
        }
    }

    public function update(NoticeReqeust $request, Notice $notice)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
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

        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $this->notice->destroy($notice);
            Toastr::success(_trans('response.Operation successful'), 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->notice->destroyAll($request);
    }

    public function userProfileTable(Request $request)
    {
        if ($request->ajax()) {
            return $this->notice->table($request);
        }
    }
}
