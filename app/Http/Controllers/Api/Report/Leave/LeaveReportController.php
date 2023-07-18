<?php

namespace App\Http\Controllers\Api\Report\Leave;

use Illuminate\Http\Request;
use App\Services\Hrm\LeaveService;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\RoleRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Repositories\Hrm\Leave\LeaveRequestRepository;

class LeaveReportController extends Controller
{

    protected LeaveRequestRepository $leaveRequest;
    protected RoleRepository $role;
    protected $service;

    public function __construct(LeaveRequestRepository $leaveRequestRepository, RoleRepository $role, LeaveService $service)
    {
        $this->leaveRequest = $leaveRequestRepository;
        $this->role = $role;
        $this->service = $service;
    }


    public function dateSummary(Request $request)
    {
        return $this->service->dateSummary($request);
    }
    public function dateSummaryList(Request $request)
    {
        return $this->service->dateSummaryList($request);
    }
    public function dateUserLeaveList(Request $request)
    {
        return $this->service->dateUserLeaveList($request);
    }
}
