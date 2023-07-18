<?php

namespace App\Services\Hrm;

use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Services\Core\BaseService;
use App\Models\Hrm\Leave\AssignLeave;
use App\Models\Hrm\Leave\LeaveRequest;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Http\Resources\Hrm\LeaveRequestCollection;
use App\Repositories\Hrm\Leave\LeaveTypeRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\InvoiceGenerateTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Leave\LeaveRequestRepository;

class LeaveService extends BaseService
{
    use RelationshipTrait, DateHandler, InvoiceGenerateTrait, ApiReturnFormatTrait;

    protected $leaveRequest;
    protected $user;
    protected $leave_type;

    public function __construct(LeaveRequest $leaveRequest, LeaveRequestRepository $leaveRequestRepository, User $user, LeaveTypeRepository $leave_type)
    {
        $this->model = $leaveRequest;
        $this->leaveRequest = $leaveRequestRepository;
        $this->user = $user;
        $this->leave_type = $leave_type;
    }

    public function leaveSummary($request=null)
    {
        if (appSuperUser() && $request!=null) {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
            }

            $userId= $request->user_id;
        } else {
           $userId= auth()->user()->id;
        }
        $user = $this->leaveRequest->getUserById($userId);
        if ($user) {
            $assignedLeaveList = AssignLeave::where(['company_id' => $this->companyInformation()->id, 'department_id' => $user->department_id])->get();
            $data = [];
            $totalLeave = 0;
            $totalUsed = 0;
            foreach ($assignedLeaveList as $key => $item) {
                $approvedLeave = $this->model->query()->where(['company_id' => $user->company_id, 'user_id' => $user->id, 'status_id' => 1, 'assign_leave_id' => $item->id])->sum('days');
                $data[$key]['id'] = $item->id;
                $data[$key]['type'] = $item->type->name;
                $data[$key]['total_leave'] = $item->days;
                $data[$key]['left_days'] = $item->days - intval($approvedLeave);
                $totalLeave += $item->days;
                $totalUsed += intval($approvedLeave);
            }
            $availableLeave['total_leave'] = $totalLeave;
            $availableLeave['total_used'] = $totalUsed;
            $availableLeave['leave_balance'] = $totalLeave - $totalUsed;
            $availableLeave['available_leave'] = $data;
            return $this->responseWithSuccess('Leave summary', $availableLeave, 200);
        } else {
            return $this->responseWithError('No role assigned yet', [], 400);
        }
    }
    public function dateSummary($request)
    {
        $validator = Validator::make(\request()->all(), [
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        //get all leave type
        $leaveTypes = $this->leave_type->getAll();
        $leave_types=[];
        foreach ($leaveTypes as $key => $item) {
            $leave_types[$key]['id'] = $item->id;
            $leave_types[$key]['name'] = $item->name;
            $leave_types[$key]['count'] = $this->model->query()
                        ->where(['leave_requests.company_id' => $this->companyInformation()->id, 'leave_requests.status_id' => 1])
                        ->leftjoin('assign_leaves', 'assign_leaves.id', '=', 'leave_requests.assign_leave_id')
                        ->where('leave_from', '<=', $request->date)
                        ->where('leave_to', '>=', $request->date)
                        ->where('assign_leaves.type_id',$item->id)
                        ->count();
        }
        $pending=[];
        $data=[];
        $data['date']=$this->dateFormatWithoutTime($request->date);
        $data['leave_types']=$leave_types;


        return $this->responseWithSuccess('Leave Summery', $data, 200);
    }
    public function dateSummaryList($request)
    {
        $validator = Validator::make(\request()->all(), [
            'date' => 'required',
            'leave_type' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
       
        $leave_requests=$this->model->query()
                    ->where(['leave_requests.company_id' => $this->companyInformation()->id, 'leave_requests.status_id' => 1])
                    ->leftjoin('assign_leaves', 'assign_leaves.id', '=', 'leave_requests.assign_leave_id')
                    ->where('assign_leaves.type_id', $request->leave_type)
                    ->where('leave_from', '<=', $request->date)
                    ->where('leave_to', '>=', $request->date)
                    ->select('leave_requests.*','assign_leaves.type_id')
                    ->get();
        $data=[];
        foreach ($leave_requests as $key => $leave) {
            $data[$key]['id']=$leave->id;
            $data[$key]['user_id']=$leave->user_id;
            $data[$key]['user_name']=$leave->user->name;
            $data[$key]['avatar']=uploaded_asset($leave->user->avatar_id);
            $data[$key]['user_designation']=$leave->user->designation->title;
            // $data[$key]['leave_from']=$this->dateFormatWithoutTime($leave->leave_from);
            // $data[$key]['leave_to']=$this->dateFormatWithoutTime($leave->leave_to);
            $data[$key]['days']=$leave->days;
            $data[$key]['reason']=ucfirst($leave->reason);
        }
        $response['leaves']=$data;
        return $this->responseWithSuccess('Leave Summery To List', $response, 200);
    }

    public function teamMemberLeaveList($request)
    {
        $teamLeadId = auth()->id();
        $teamMembers = $this->user->query()
            ->where(['company_id' => $this->companyInformation()->id, 'manager_id' => $teamLeadId])
            ->select('id')
            ->pluck('id');

        $leaveRequests = $this->model->query()
            ->whereIn('user_id', $teamMembers)
            ->orderBy('apply_date', 'DESC')
            ->get();
        $data = new LeaveRequestCollection($leaveRequests);

        return $this->responseWithSuccess('Team members leave requests', $data, 200);
    }

    public function dateUserLeaveList($request)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }

        $user = $this->user->find($request->user_id);

        $data=[];
            if ($user) {
                    $leaveRequests = $this->model->query()->where('user_id', $user->id)
                    ->where(function ($query) use ($request) {
                        $query->where('leave_from', 'like', $request->month . '%')
                            ->orWhere('leave_to', 'like', $request->month . '%');
                    })
                    ->leftjoin('assign_leaves', 'assign_leaves.id', '=', 'leave_requests.assign_leave_id')
                    ->leftjoin('leave_types', 'leave_types.id', '=', 'assign_leaves.type_id')
                    ->where('leave_requests.status_id', 1)
                    ->orderBy('leave_requests.id', 'DESC')
                    ->select('leave_requests.*','assign_leaves.type_id','leave_types.name as leave_type')
                    ->get();
                    foreach ($leaveRequests as $key => $leave) {
                        $data[$key]['id']=$leave->id;
                        $data[$key]['leave_from']=$this->dateFormatWithoutTime($leave->leave_from);
                        $data[$key]['leave_to']=$this->dateFormatWithoutTime($leave->leave_to);
                        $data[$key]['leave_type']=$leave->leave_type;
                        $data[$key]['days']=$leave->days;
                        $data[$key]['reason']=ucfirst($leave->reason);
                    }
                return $this->responseWithSuccess('Users leave requests', $data, 200);
            } else {
                return $this->responseWithError('No data found', [], 400);
            }
        }
}