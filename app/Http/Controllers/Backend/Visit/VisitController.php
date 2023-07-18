<?php

namespace App\Http\Controllers\Backend\Visit;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\Hrm\Visit\VisitRepository;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Department\DepartmentRepository;

class VisitController extends Controller
{

    use RelationshipTrait;

    protected $departmentRepository;
    protected $visitRepository;

    public function __construct(DepartmentRepository $departmentRepository, VisitRepository $visitRepository)
    {
        $this->departmentRepository = $departmentRepository;
        $this->visitRepository = $visitRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->visitRepository->table($request);
        }
        $data['fields'] = $this->visitRepository->fields();
        $data['checkbox'] = true;
        $data['table']    = route('visit.index');
        $data['url_id']    = 'visit_table_url';
        $data['class']     = 'table_class';


        $data['title'] = _trans('common.Visit');
        $data['departments'] = $this->departmentRepository->getAll();
        $data['visits'] = $this->visitRepository->getAll();
        return view('backend.visit.index', compact('data'));
    }

    public function visit($id)
    {
        try {
            $data['id'] = $id;
            $data['title'] = _trans('common.Visit List');
            return view('backend.user.visit', compact('data'));
        } catch (\Exception $exception) {
            Toastr::error('Something went wrong.', 'Error');
        }
    }

    public function visitDatatable(Request $request, $userId)
    {
        try {
            return $this->visitRepository->getListForWeb($request, $userId);
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function visitHistory($id)
    {
        try {
            $data['id'] = $id;
            $data['title'] = _trans('common.Visit History');
            return view('backend.user.visit_history', compact('data'));
        } catch (\Exception $exception) {
            Toastr::error('Something went wrong.', 'Error');
        }
    }

    public function visitHistoryDatatable(Request $request)
    {
        try {
            return $this->visitRepository->visitHistoryDatatable($request, 'visit_history');
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function visitDetails($id)
    {
        try {
            $data['title'] = _trans('common.Visit Details');
            $data['visit'] = $this->visitRepository->getVisitDetails($id);
            return view('backend.visit.visit_details', compact('data'));
        } catch (\Throwable $exception) {
            Toastr::error('Something went wrong.', 'Error');
            return redirect()->back();
        }
    }


    public function userProfileTable(Request $request)
    {
        if ($request->ajax()) {
            return $this->visitRepository->table($request);
        }
    }
}
