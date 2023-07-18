<?php

namespace App\Http\Controllers\Backend\Team;

use Illuminate\Http\Request;
use App\Models\Hrm\Team\Team;
use App\Http\Requests\TeamRequest;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\Team\TeamRepository;
use App\Repositories\Admin\RoleRepository;
use App\Repositories\Hrm\Leave\LeaveRequestRepository;
use App\Repositories\Hrm\Department\DepartmentRepository;

class TeamController extends Controller
{
    protected $team;
    protected $leaveRequest;
    protected $role;

    public function __construct(TeamRepository $teamRepo, LeaveRequestRepository $leaveRequestRepository, RoleRepository $role)
    {
        $this->team = $teamRepo;
        $this->leaveRequest = $leaveRequestRepository;
        $this->role = $role;
    }

    public function index()
    {
        // $team = Team::first();
        $data['title'] = _trans('common.Team List');
        return view('backend.team.index', compact('data'));
    }
    public function create()
    {
        // $team = Team::first();
        $data['title'] = _trans('common.Create Team');
        return view('backend.team.create', compact('data'));
    }

    public function dataTable(Request $request)
    {
        return $this->team->dataTable($request);
    }
    public function store(TeamRequest $request)
    {
        try {
            $this->team->teamStore($request);
            Toastr::success('Team Created Successfully', 'Success');
            return redirect()->route('team.index');
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function leaveList()
    {
        try {
            $data['title'] = _trans('common.Leave Request');
            $data['departments'] = resolve(DepartmentRepository::class)->getAll();
            $data['url'] = route('team_member_leave_datatable');
            return view('backend.leave.leaveRequest.index', compact('data'));
        } catch (\Exception $exception) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function leaveDatatable(Request $request)
    {
        return $this->leaveRequest->teamMemberLeaveDataTable($request);
    }
}
