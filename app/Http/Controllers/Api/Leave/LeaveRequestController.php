<?php

namespace App\Http\Controllers\Api\Leave;

use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hrm\AssignedLeavesCollection;
use App\Models\Hrm\Leave\LeaveRequest;
use App\Repositories\Admin\RoleRepository;
use App\Repositories\Hrm\Leave\LeaveRequestRepository;
use App\Services\Hrm\LeaveService;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{

    use ApiReturnFormatTrait;

    protected LeaveRequestRepository $leaveRequest;
    protected RoleRepository $role;
    protected $service;

    public function __construct(LeaveRequestRepository $leaveRequestRepository, RoleRepository $role, LeaveService $service)
    {
        $this->leaveRequest = $leaveRequestRepository;
        $this->role = $role;
        $this->service = $service;
    }


    public function leaveSummary(Request $request)
    {
        return $this->service->leaveSummary($request);
    }

    public function leaveListView(Request $request)
    {
        return $this->leaveRequest->totalLeaveRequestList($request);
    }

    public function leaveDetails(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        return $this->leaveRequest->leaveDetailsInformation($request, $id);
    }

    public function store(Request $request)
    {
        try {
            return $this->leaveRequest->store($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            return $this->leaveRequest->show($request, $id);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    public function getAvailableLeave(Request $request)
    {
        return $this->leaveRequest->availableLeave($request);
    }

    public function cancelLeaveRequest($id): \Illuminate\Http\JsonResponse
    {
        $status = $this->leaveRequest->approveOrRejectOrCancel($id, 7);
        if ($status) {
            return $this->responseWithSuccess('Leave request cancelled', [], 200);
        } else {
            return $this->responseWithError('No data found', [], 400);
        }
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->leaveRequest->update($request, $id);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }


    //methods for leave approval
    public function approvalLeaveList()
    {
        return $this->leaveRequest->approvalLeaveListView();
    }


    public function approveOrRejectLeaveRequest($id, $status): \Illuminate\Http\JsonResponse
    {
        $data = $this->leaveRequest->approveOrRejectOrCancel($id, $status);
        if ($data) {
            return $this->responseWithSuccess('Leave status updated successfully', [], 200);
        } else {
            return $this->responseWithError('No data found', [], 400);
        }
    }

    public function teamMemberLeaveList(Request $request)
    {
        return $this->service->teamMemberLeaveList($request);
    }

    public function statusChange($id, $status)
    {
        $status = $this->leaveRequest->approveOrRejectOrCancel($id, $status);
        if ($status) {
            return $this->responseWithSuccess('Leave status updated successfully', [], 200);
        } else {
            return $this->responseWithError('No data found', [], 400);
        }
    }

}
