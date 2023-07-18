<?php

namespace App\Http\Controllers\Api\Leave;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Repositories\Hrm\Leave\LeaveRequestRepository;

class DailyLeaveController extends Controller
{
    use ApiReturnFormatTrait;
    protected $leaveRequest;
    public function __construct(LeaveRequestRepository $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    public function store(Request $request)
    {

        try {
            return $this->leaveRequest->storeDailyLeave($request);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 500);
        }
    }

    public function listView(Request $request)
    {
        try {
            return $this->leaveRequest->dailyLeaveListView($request);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 500);
        }
    }

    public function staffListView(Request $request)
    {

        try {
            return $this->leaveRequest->staffListView($request);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 500);
        }
    }

    public function monthlySummeryView(Request $request)
    {

        try {
            return $this->leaveRequest->monthlySummeryView($request->all());
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 500);
        }
    }
    public function LeaveView(Request $request)
    {

        try {
            return $this->leaveRequest->LeaveView($request);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 500);
        }
    }

    public function approveRejectLeave(Request $request)
    {
        try {
            return $this->leaveRequest->approveRejectLeave($request);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 500);
        }
    }
}
