<?php

namespace App\Http\Controllers\Backend\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\Report\BreakReportRepository;
use App\Repositories\Hrm\Department\DepartmentRepository;

class BreakReportController extends Controller
{
    protected $breakReport;
    protected $department;
    protected $users;

    public function __construct(BreakReportRepository $breakReport, DepartmentRepository $department, UserRepository $users)
    {
        $this->breakReport = $breakReport;
        $this->department = $department;
        $this->users = $users;
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $this->breakReport->table($request);
            }
            $data['table']    = route('breakReport.index');
            $data['url_id']    = 'break_table_url';
            $data['class']     = 'table_class';

            $data['fields'] = $this->breakReport->fields();
            $data['checkbox'] = true;

            $data['title'] = _trans('common.Break History');
            $data['departments'] = $this->department->getAll();
            return view('backend.report.break.index', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function dataTable()
    {
        return $this->breakReport->dataTable();
    }
}
