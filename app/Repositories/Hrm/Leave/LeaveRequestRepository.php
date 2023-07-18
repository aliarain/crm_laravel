<?php

namespace App\Repositories\Hrm\Leave;

use Validator;
use App\Models\User;
use AWS\CRT\HTTP\Request;
use Illuminate\Support\Str;
use App\Models\Role\RoleUser;
use App\Services\Hrm\LeaveService;
use Illuminate\Support\Facades\DB;
use App\Models\Hrm\Leave\LeaveType;
use Illuminate\Support\Facades\Log;
use App\Models\Hrm\Leave\DailyLeave;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\Hrm\Leave\AssignLeave;
use App\Models\Hrm\Leave\LeaveRequest;
use App\Models\ActivityLogs\AuthorInfo;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Notifications\HrmSystemNotification;
use App\Helpers\CoreApp\Traits\AuthorInfoTrait;
use App\Http\Resources\Hrm\DailyLeaveCollection;
use App\Http\Resources\Hrm\LeaveRequestCollection;
use App\Http\Resources\Hrm\LeaveApprovalCollection;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\FirebaseNotification;
use App\Models\coreApp\Relationship\RelationshipTrait;

class LeaveRequestRepository
{
    use FileHandler, DateHandler, ApiReturnFormatTrait, AuthorInfoTrait, RelationshipTrait, FirebaseNotification;

    protected LeaveRequest $leaveRequest;
    protected UserRepository $userRepository;
    public $leaveService;

    public function __construct(
        LeaveRequest   $leaveRequest,
        UserRepository $userRepository
    )
    {
        $this->leaveRequest = $leaveRequest;
        $this->userRepository = $userRepository;
    }

    public function getUserById($id)
    {
        return User::with('userRole')->find($id);
    }

    public function getUserRole($userId)
    {
        return RoleUser::where('user_id', $userId)->first();
    }

    public function getLeaveType()
    {
        return LeaveType::where('company_id', $this->companyInformation()->id)->get();
    }

    public function getUserAssignLeave()
    {
        return AssignLeave::with('type')->where([
            'company_id' => $this->companyInformation()->id,
            'department_id' => auth()->user()->department_id
        ])->get();
    }

    public function getAssignLeave()
    {
        return AssignLeave::get();
    }

    public function getAssignLeaveByRoleId($roleId)
    {
        return AssignLeave::where('role_id', $roleId)->get();
    }

    public function approvedOrRejectedByUser($leaveRequest)
    {
        $author = AuthorInfo::where([
            'authorable_type' => get_class($leaveRequest),
            'authorable_id' => $leaveRequest->id,
        ])->first();
        if ($author) {
            //check leave request status first
            if ($leaveRequest->status_id == 1) {
                if ($author->approveUser) {
                    return $author->approveUser->name;
                } else {
                    return null;
                }
            } elseif ($leaveRequest->status_id == 6) {
                if ($author->rejectedUser) {
                    return $author->rejectedUser->name;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function index()
    {
    }


    public function leaveDetailsInformation($request, $id)
    {
        $user = $this->getUserById($request->user_id);
        if ($user) {
            $leaveRequest = $this->leaveRequest->query()->where(['id' => $id, 'user_id' => $user->id])->first();
            if ($leaveRequest) {
                $data['id'] = $leaveRequest->id;
                $data['user_id'] = @$leaveRequest->user->id;
                $data['name'] = @$leaveRequest->user->name;
                $data['designation'] = @$leaveRequest->user->designation->title;
                $data['department'] = @$leaveRequest->user->department->title;
                $data['employee_id'] = @$leaveRequest->user->employee_id;
                $data['requested_on'] = $this->getMonthDateStringWithTime($leaveRequest->created_at);
                $data['type'] = @$leaveRequest->assignLeave->type->name;
                $data['period'] = "{$this->getMonthDate($leaveRequest->leave_from)} - {$this->getMonthDate($leaveRequest->leave_to)}";
                $data['total_days'] = $leaveRequest->days;
                $data['note'] = $leaveRequest->reason;
                $data['apporover'] = @$leaveRequest->user->manager->name;
                $leaveSummary = resolve(LeaveService::class)->leaveSummary();
                if ($leaveSummary->original['result']) {
                    $data['available_leave'] = $leaveSummary->original['data']['leave_balance'];
                    $data['total_used'] = $leaveSummary->original['data']['total_used'];
                } else {
                    $data['available_leave'] = 0;
                }

                $approvedBy = AuthorInfo::where(['authorable_type' => get_class($this->leaveRequest), 'authorable_id' => $leaveRequest->id])->first();
                if ($approvedBy) {
                    $data['approved_by'] = @$approvedBy->approveUser->name ?? '';
                } else {
                    $data['approved_by'] = "";
                }
                $referredBy = AuthorInfo::where(['authorable_type' => get_class($this->leaveRequest), 'authorable_id' => $leaveRequest->id])->first();
                if ($referredBy) {
                    $data['referred_by'] = @$referredBy->referredUser->name ?? '';
                } else {
                    $data['referred_by'] = "";
                }
                $data['status'] = @$leaveRequest->status->name;
                $data['color_code'] = appColorCodePrefix() . @$leaveRequest->status->color_code;
                if ($leaveRequest->substitute) {
                    $managerData['id'] = @$leaveRequest->substitute->id;
                    $managerData['name'] = @$leaveRequest->substitute->name;
                    $managerData['employee_id'] = @$leaveRequest->substitute->employee_id;
                    $managerData['designation'] = @$leaveRequest->substitute->designation->title;
                    $managerData['avatar'] = uploaded_asset($leaveRequest->substitute->avatar_id);
                    $data['substitute'] = $managerData;
                } else {
                    $data['substitute'] = null;
                }
                $data['attachment'] = uploaded_asset($leaveRequest->attachment_file_id);
                return $this->responseWithSuccess('Leave request details', $data, 200);
            } else {
                return $this->responseWithError('No data found', [], 400);
            }
        } else {
            return $this->responseWithError('No data found', [], 400);
        }
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'attachment_file' => 'sometimes|max:2048',
            'apply_date' => 'required',
            'leave_from' => 'required',
            'leave_to' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.File max size validation'), $validator->errors(), 422);
        }

        try {
            
            $userDepartment = $this->getUserById($request->user_id);
            //check this user has appropriate role
            if ($userDepartment) {
                $leaveType = AssignLeave::whereId($request->assign_leave_id)->first();
                //check is leave assigned
                if ($leaveType) {
                    //check assign leave and user role id is same then store data to related tables
                    if ($leaveType->department_id == $userDepartment->department_id) {
                        $user = $this->userRepository->getById($request->user_id);
                        if ($user) {
                            $leaveRequest = new  $this->leaveRequest();
                            $leaveRequest->user_id = $user->id;
                            $leaveRequest->company_id = $user->company->id;
                            $leaveRequest->assign_leave_id = $leaveType->id;
                            $leaveRequest->substitute_id = $request->substitute_id;
                            $leaveRequest->apply_date = $request->apply_date;
                            $leaveRequest->leave_from = $request->leave_from;
                            $leaveRequest->leave_to = $request->leave_to;
                            $leaveRequest->days = $this->dateDiff($request->leave_from, $request->leave_to);
                            $leaveRequest->reason = $request->reason;
                            $leaveRequest->status_id = 2;

                            //if there exists any attachment file then upload here
                            if ($request->hasFile('attachment_file')) {
                                $leaveRequest->attachment_file_id = $this->uploadImage($request->attachment_file, 'uploads/employeeDocuments')->id;
                            }
                            $leaveRequest->save();


                            //created by instance here
                            $author = $this->createdBy($leaveRequest);
                            $leaveRequest->author_info_id = $author->id;
                            $leaveRequest->save();

                            $notify_body = $leaveRequest->assignLeave->type->name." Requested by " . auth()->user()->name;
                            $details = [
                                'title' => 'New Leave Request',
                                'body' => $notify_body,
                                'actionText' => 'View',
                                'actionURL' => [
                                    'app' => 'leave_request',
                                    'web' => route('leaveRequest.index'),
                                    'target' => '_blank',
                                ],
                                'sender_id' => $user->id
                            ];
                            //send notification to manager
                            if ($leaveRequest->user->manager_id != null) {
                              $this->sendFirebaseNotification($leaveRequest->user->manager_id, 'leave_approved', $leaveRequest->id, route('leaveRequest.index'));

                                sendDatabaseNotification($leaveRequest->user->manager, $details);
                            }
                            if ($leaveRequest->user->myHr() != null) {
                                sendDatabaseNotification($leaveRequest->user->myHr(), $details);
                            }

                            return $this->responseWithSuccess('Leave request has been created', [], 200);
                        } else {
                            return $this->responseWithError('No user found', [], 400);
                        }
                    } else {
                        return $this->responseWithError('Leave is not available for this user! Please try again', [], 400);
                    }
                } else {
                    return $this->responseWithError('Please assign leave first', [], 400);
                }
            } else {
                return $this->responseWithError('This user has no role', [], 400);
            }
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    public function isExistsLeave($request): bool
    {
        $countRequestDate = $this->dateDiff($request->leave_from, $request->leave_to);
        $roleUser = $this->getUserRole($request->user_id);

        $getExistsDate = AssignLeave::where(
            [
                'role_id' => $roleUser->role_id,
                'type_id' => $request->type_id
            ]
        )->first();
        if (@$getExistsDate->days < $countRequestDate) {
            return false;
        } else {
            return true;
        }
    }


    public function show($id)
    {
        return $this->leaveRequest->query()->where('id', $id)->first();
    }

    public function update($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'attachment_file' => 'sometimes|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.File max size validation'), $validator->errors(), 422);
        }

        $user = $this->getUserById($request->user_id);
        if ($user) {
            $leaveRequest = $this->leaveRequest->query()->where(['id' => $id, 'user_id' => $user->id])->first();
            if ($leaveRequest) {
                $leaveRequest->reason = $request->reason;
                $leaveRequest->substitute_id = $request->substitute_id;
                //if there exists any attachment file then upload here
                if ($request->hasFile('attachment_file')) {
                    $leaveRequest->attachment_file_id = $this->uploadImage($request->attachment_file, 'uploads/employeeDocuments')->id;
                }
                $leaveRequest->save();

                //created by instance here
                $author = $this->updatedBy($leaveRequest);
                $leaveRequest->author_info_id = $author->id;
                $leaveRequest->save();
                return $this->responseWithSuccess('Leave request details', $leaveRequest, 200);
            } else {
                return $this->responseWithError('No data found', [], 400);
            }
        } else {
            return $this->responseWithError('No data found', [], 400);
        }
    }

    public function dataTable($request)
    {
        $leaveRequest = $this->leaveRequest->query();
        $leaveRequest = $leaveRequest->where('company_id', auth()->user()->company_id);
        if (auth()->user()->role->slug == 'staff') {
            $leaveRequest = $leaveRequest->where('user_id', auth()->id());
        } else {
            $leaveRequest->when(\request()->get('user_id'), function (Builder $builder) {
                return $builder->where('user_id', \request()->get('user_id'));
            });
        }
        $leaveRequest->when(\request()->get('daterange'), function (Builder $builder) {
            $date = explode(' - ', \request()->get('daterange'));
            return $builder->whereBetween('apply_date', [$this->databaseFormat($date[0]), $this->databaseFormat($date[1])]);
        });
        $leaveRequest->when(\request()->get('short_by'), function (Builder $builder) {
            return $builder->where('status_id', \request()->get('short_by'));
        });
        $leaveRequest->when(\request()->get('department_id'), function (Builder $builder) {
            return $builder->whereHas('assignLeave', function (Builder $builder) {
                return $builder->where('department_id', \request()->get('department_id'));
            });
        });
        return $this->leaveDataTable($leaveRequest);
    }

    public function teamMemberLeaveDataTable($request, $id = null)
    {
        $teamLeadId = auth()->id();
        $teamMembers = User::query()
            ->where(['company_id' => $this->companyInformation()->id, 'manager_id' => $teamLeadId])
            ->select('id')
            ->pluck('id');
        $leaveRequest = $this->leaveRequest->query()
            ->where('company_id', $this->companyInformation()->id)
            ->whereIn('user_id', $teamMembers);
        $leaveRequest->when(\request()->get('status_id'), function (Builder $builder) {
            return $builder->where('status_id', \request()->get('status_id'));
        });
        $leaveRequest->when(\request()->get('from_date') && \request()->get('to_date'), function (Builder $builder) {
            return $builder->whereBetween('created_at', start_end_datetime(\request()->get('from_date'), \request()->get('to_date')));
        });
        $leaveRequest->when(\request()->get('department_id'), function (Builder $builder) {
            return $builder->whereHas('assignLeave', function (Builder $builder) {
                return $builder->where('department_id', \request()->get('department_id'));
            });
        });

        if (@$request->from && @$request->to) {
            $leaveRequest = $leaveRequest->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        return $this->leaveDataTable($leaveRequest);
    }

    public function leaveDataTable($leaveRequest)
    {
        return datatables()->of($leaveRequest->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                $view = _trans('common.View');
                $approve = _trans('common.Approve');
                $reject = _trans('common.Reject');
                $refer = _trans('common.Refer');
                $delete = _trans('common.Delete');
                if (hasPermission('leave_request_read')) {
                    $action_button .= '<a href="' . route('leaveRequest.view', $data->id) . '" class="dropdown-item"> '. $view .' </a>';
                }

                if (hasPermission('leave_request_approve')) {
                    if ($data->status_id == 6 || $data->status_id == 17) {
                        $action_button .= actionButton($approve, 'ApproveOrReject(' . $data->id . ',' . "1" . ',`hrm/leave/request/approve-or-reject/`,`Approve`)', 'approve');
                    }
                    if ($data->status_id == 2) {
                        $action_button .= actionButton($approve, 'ApproveOrReject(' . $data->id . ',' . "1" . ',`hrm/leave/request/approve-or-reject/`,`Approve`)', 'approve');
                        $action_button .= actionButton($reject, 'ApproveOrReject(' . $data->id . ',' . "6" . ',`hrm/leave/request/approve-or-reject/`,`Reject`)', 'reject');
                    }
                }
                
                if (hasPermission('leave_request_approve')) {
                    if ($data->status_id == 1) {
                        $action_button .= actionButton($reject, 'ApproveOrReject(' . $data->id . ',' . "6" . ',`hrm/leave/request/approve-or-reject/`,`Reject`)', 'reject');
                    }
                }

                if (hasPermission('leave_request_delete')) {
                    $action_button .= actionButton($delete, '__globalDelete(' . $data->id . ',`hrm/leave/request/delete/`)', 'delete');
                }
                if (hasPermission('team_update')) {
                    if ($data->status_id != 17) {
                        $action_button .= actionButton($refer, 'ApproveOrReject(' . $data->id . ',' . "17" . ',`hrm/leave/request/approve-or-reject/`,`Refer`)', 'approve');
                    }
                }
                
                if (hasPermission('leave_request_approve') || hasPermission('team_update')) {
                    $button = '<div class="flex-nowrap">
                    <div class="dropdown">
                        <button class="btn btn-white dropdown-toggle align-text-top action-dot-btn" data-boundary="viewport" data-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">' . $action_button . '</div>
                    </div>
                </div>';
                    return $button;
                }
            })
            ->addColumn('file', function ($data) {
                if ($data->attachment_file_id !=null) {
                    return '<a href="' . uploaded_asset($data->attachment_file_id) . '" download class="btn btn-white btn-sm"><i class="fas fa-download"></i></a>';
                }else{
                    return _trans('common.No File');
                }})
            ->addColumn('name', function ($data) {
                return $data->user->name;
            })
            ->addColumn('type', function ($data) {
                return @$data->assignLeave->type->name;
            })
            ->addColumn('date', function ($data) {
                return $data->leave_from;
            })
            ->addColumn('days', function ($data) {
                return $data->days;
            })
            ->addColumn('available_days', function ($data) {
                $leaveSummary = resolve(LeaveService::class)->leaveSummary();
                if ($leaveSummary->original['result']) {
                    return $leaveSummary->original['data']['leave_balance'];
                } else {
                    return 0;
                }
            })
            ->addColumn('substitute', function ($data) {
                return @$data->substitute->name;
            })
            ->addColumn('manager_approved', function ($data) {
                $referredBy = AuthorInfo::where(['authorable_type' => get_class($this->leaveRequest), 'authorable_id' => $data->id])->first();
                if ($referredBy) {
                    return isset($referredBy->referredUser->name) ? _trans('common.Yes') : _trans('attendance.Pending');
                }
            })
            ->addColumn('hr_approved', function ($data) {
                $approvedBy = AuthorInfo::where(['authorable_type' => get_class($this->leaveRequest), 'authorable_id' => $data->id])->first();
                if ($approvedBy) {
                    return isset($approvedBy->approveUser->name) ? _trans('common.Yes') : _trans('attendance.Pending');
                }
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->rawColumns(array('name', 'type', 'days', 'date', 'available_days', 'substitute', 'manager_approved', 'status','file', 'action'))
            ->make(true);
    }

    public function profileDataTable($request, $id = null)
    {
        $leaveRequest = $this->leaveRequest->where('user_id', $request->user_id);
        if (@$request->daterange) {
            $dateRange = explode(' - ', $request->daterange);
            $from = date('Y-m-d', strtotime($dateRange[0]));
            $to = date('Y-m-d', strtotime($dateRange[1]));
            $leaveRequest = $leaveRequest->whereBetween('leave_from', start_end_datetime($from, $to));
        }
        if (@$id) {
            $leaveRequest = $leaveRequest->where('id', $id);
        }

        if ($request->short_by) {
            $leaveRequest = $leaveRequest->where('status_id', $request->short_by);
        }

        return datatables()->of($leaveRequest->latest()->get())
            ->addColumn('name', function ($data) {
                return $data->user->name;
            })
            ->addColumn('type', function ($data) {
                return @$data->assignLeave->type->name;
            })
            ->addColumn('days', function ($data) {
                return $this->dateDiff($data->leave_from, $data->leave_to);
            })
            ->addColumn('file', function ($data) {
                return '<a href="' . uploaded_asset($data->file_id) . '" target="_blank"> <img height="40px" width="40px" src="' . uploaded_asset($data->file_id) . '"/> </a>';
            })
            ->addColumn('team_leader', function ($data) {
                return @$data->substitute->name;
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->rawColumns(array('name', 'type', 'days', 'team_leader', 'file', 'status'))
            ->make(true);
    }

    public function approveOrRejectOrCancel($id, $status): bool
    {
        $leaveRequest = $this->leaveRequest->query()->find($id);
        if ($leaveRequest) {
            $leaveRequest->status_id = $status;
            $leaveRequest->save();
            if ($status == 1) {
                //send notification to manager
                if ($leaveRequest->user->manager_id != null) {
                    $this->sendFirebaseNotification($leaveRequest->user->manager_id, 'leave_approved', $leaveRequest->id, 'leave');
                }
                //send notification to staff
                $this->sendFirebaseNotification($leaveRequest->user->id, 'leave_approved', $leaveRequest->id, 'leave');
                $this->approvedBy($leaveRequest);
            } elseif ($status == 6) {
                //send notification to staff
                $this->sendFirebaseNotification($leaveRequest->user->id, 'leave_rejected', $leaveRequest->id, 'leave');
                $this->rejectedBy($leaveRequest);
            } elseif ($status == 7) {
                //send notification to staff
                $this->sendFirebaseNotification($leaveRequest->user->id, 'leave_cancelled', $leaveRequest->id, 'leave');
                $this->cancelledBy($leaveRequest);
            } elseif ($status == 17) {
                //send notification to HR
                $this->sendFirebaseNotification(3, 'leave_referred', $leaveRequest->id, 'leave');
                $this->referredBy($leaveRequest);
            } else {
                //send notification to staff
                $this->sendFirebaseNotification($leaveRequest->user->id, 'leave_rejected', $leaveRequest->id, 'leave');
                $this->rejectedBy($leaveRequest);
            }
            return true;
        } else {
            return false;
        }
    }

    public function destroy($id)
    {
        $table_name=$this->leaveRequest->getTable();
        $foreign_id= \Illuminate\Support\Str::singular($table_name).'_id';
        return \App\Services\Hrm\DeleteService::deleteData($table_name, $foreign_id, $id);
    }

    public function availableLeave($request)
    {
        $user = $this->getUserById($request->user_id);
        if ($user) {
            $assignedLeaveList = AssignLeave::where(['company_id' => $this->companyInformation()->id, 'department_id' => $user->department_id])->get();
            $data = [];
            foreach ($assignedLeaveList as $key => $item) {
                $approvedLeave = $this->leaveRequest->query()->where(['company_id' => $user->company_id, 'user_id' => $user->id, 'status_id' => 1, 'assign_leave_id' => $item->id])->sum('days');
                $data[$key]['id'] = $item->id;
                $data[$key]['type'] = $item->type->name;
                $data[$key]['total_leave'] = $item->days;
                $data[$key]['left_days'] = $item->days - intval($approvedLeave);
            }
            $availableLeave['available_leave'] = $data;
            return $this->responseWithSuccess('Leave available for this user', $availableLeave, 200);
        } else {
            return $this->responseWithError('No role assigned yet', [], 400);
        }
    }

    public function totalLeaveRequestList($request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'month' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        $user = $this->getUserById($request->user_id);
        if ($user) {
            $leaveRequests = $this->leaveRequest->query()
            ->where('user_id', $user->id)
            ->where(function($query) use($request) {
                $query->where('leave_from', 'LIKE', '%'. $request->month . '%')
                ->orWhere('leave_to', 'LIKE', '%'. $request->month . '%');
            })
            ->get();
            $data = new LeaveRequestCollection($leaveRequests);
            return $this->responseWithSuccess('Users total leave requests', $data, 200);
        } else {
            return $this->responseWithError('No data found', [], 400);
        }
    }

    //leave approval methods

    public function approvalLeaveListView()
    {
        $userId = auth()->id();
        $getUserIds = $this->userRepository->model->query()
            ->when($userId != auth()->user()->myHr()->id, function($query) use($userId) {
                $query->where('manager_id', $userId);
            })
            ->where('status_id', 1)
            ->where('company_id', $this->companyInformation()->id)
            ->select('id', 'manager_id', 'status_id')->pluck('id')->toArray();
        $leaveRequests = $this->leaveRequest->query()->whereIn('user_id', $getUserIds)->orderByDesc('id')->get();
        $data = new LeaveApprovalCollection($leaveRequests);
        return $this->responseWithSuccess('Users total leave requests', $data, 200);
    }

    public function storeDailyLeave($request)
    {
        $validator = Validator::make($request->all(), [
            'leave_type' => 'required|in:early_leave,late_arrive',
            'approx_time' => 'required',
            'reason' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }

        $daily_leave=new DailyLeave();
        $daily_leave->user_id=auth()->id();
        $daily_leave->leave_type=$request->leave_type;
        $daily_leave->date=date('Y-m-d');
        $daily_leave->time=$request->approx_time;
        $daily_leave->reason=$request->reason;
        $daily_leave->company_id=$this->companyInformation()->id;
        // $daily_leave->save();
        $formated_leave_type=str_replace('_',' ',Str::title($daily_leave->leave_type));
        if ($daily_leave->save()) {
            $notify_body = $formated_leave_type." Requested by " . auth()->user()->name;
                $details = [
                    'title' => 'New '.$formated_leave_type.' Request',
                    'body' => $notify_body,
                    'actionText' => 'View',
                    'actionURL' => [
                        'app' => 'daily_leave',
                        'web' => '',
                        'target' => '_blank',
                    ],
                    'sender_id' => auth()->id()
                ];
                //send notification to manager
                if ($daily_leave->user->manager_id != null) {
                    $this->sendFirebaseNotification($daily_leave->user->manager_id, 'daily_leave', $daily_leave->id, route('leaveRequest.index'));

                    sendDatabaseNotification($daily_leave->user->manager, $details);
                }
                if ($daily_leave->user->myHr() != null) {
                    sendDatabaseNotification($daily_leave->user->myHr(), $details);
                }
        }
        return $this->responseWithSuccess('Daily Leave Requested', [], 200);

    }
    public function staffListView($request)
    {

        $leaveRequests = DailyLeave::query();
        if ($request->leave_type) {
            $leaveRequests->where('leave_type', $request->leave_type);
        }
        if ($request->month) {
            $leaveRequests->where('date', 'LIKE', '%'. $request->month . '%');
        }
        if ($request->user_id) {
            $leaveRequests->where('user_id', $request->user_id);
        }
        if ($request->leave_status == 'approved') {
            $leaveRequests->where(function($query){
                $query->where('approved_at_tl', '!=', null)->orWhere('approved_at_hr', '!=', null);
            });
        }else if ($request->leave_status == 'pending') {
            $leaveRequests->where('approved_at_tl', null)->where('approved_at_hr', null)->where('rejected_at_hr', null)->where('rejected_at_tl', null); // all should be nulled
        }else if ($request->leave_status == 'rejected') {
            $leaveRequests->where(function($query){
                $query->where('rejected_at_hr', '!=', null)->orWhere('rejected_at_tl', '!=', null);
            });  //anyone rejected leave
        }
        $leaveRequests->orderByDesc('id');
        $data = new DailyLeaveCollection($leaveRequests->get());
        return $this->responseWithSuccess('Users total leave requests', $data, 200);

    }
    public function dailyLeaveNotification($daily_leave,$status,$route=null)
    {
        $formated_leave_type=str_replace('_',' ',Str::title($daily_leave->leave_type));
        $notify_body = $formated_leave_type." Request ".$status." by " . auth()->user()->name;
        $details = [
            'title' => $formated_leave_type.' Request '.$status,
            'body' => $notify_body,
            'actionText' => 'View',
            'actionURL' => [
                'app' => 'daily_leave',
                'web' => '',
                'target' => '_blank',
            ],
            'sender_id' => auth()->id()
        ];

            $this->sendFirebaseNotification($daily_leave->user->id, 'daily_leave', $daily_leave->id, route('leaveRequest.index'));

            sendDatabaseNotification($daily_leave->user, $details);
    }

    public function approveRejectLeave($request)
    {
        $validator = Validator::make($request->all(), [
            'leave_id' => 'required',
            'leave_status' => 'required|in:approved,rejected',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        $leaveRequests = DailyLeave::query();
        $leaveRequest = $leaveRequests->find($request->leave_id);

        if ($leaveRequest) {
            if(Auth::id()==$leaveRequest->user->manager->id){
                if ($request->leave_status == 'approved') {
                    $leaveRequest->approved_at_tl = date('Y-m-d H:i:s');
                    $leaveRequest->approved_by_tl = auth()->id();
                } else if ($request->leave_status == 'rejected') {
                    $leaveRequest->rejected_at_tl = date('Y-m-d H:i:s');
                    $leaveRequest->rejected_by_tl = auth()->id();
                }
            }else if(Auth::id()==$leaveRequest->user->myHr()->id){
                if ($request->leave_status == 'approved') {
                    $leaveRequest->approved_by_hr = Auth::id();
                    $leaveRequest->approved_at_hr = date('Y-m-d H:i:s');
                } else if ($request->leave_status == 'rejected') {
                    $leaveRequest->rejected_by_hr = Auth::id();
                    $leaveRequest->rejected_at_hr = date('Y-m-d H:i:s');
                }
            }
            else{
                return $this->responseWithError('You are not authorized to approve or reject this leave', [], 400);
            }

            $leaveRequest->save();

            try {
                $this->dailyLeaveNotification($leaveRequest,$request->leave_status);
            } catch (\Throwable $th) {
            }

            return $this->responseWithSuccess('Leave request'.' '.$request->leave_status, [], 200);
        }
    }

    public function dailyLeaveListView($request)
    {
        try {


            if($request['date']){
                $month = $request['date'];
            }else{
                $month = date('Y-m-d');
            }



            $userId = auth()->id();

            $getUserIds[] = auth()->id();
            if(auth()->user()->is_hr){
                $getUserIds = $this->userRepository->model->query()
                ->where(['status_id' => 1, 'company_id' => $this->companyInformation()->id])
                ->select('id')->pluck('id')->toArray();
            }



            $leaveRequests=DailyLeave::query();

            $leaveRequests = $leaveRequests->whereIn('user_id',$getUserIds)->orderByDesc('id');
            if($month){
                $leaveRequests = $leaveRequests->where('date', 'LIKE', '%'. $month . '%');
            }
            if ($request['leave_type']) {
                $leaveRequests = $leaveRequests->where('leave_type', $request['leave_type']);
            }
            $leaveRequests=$leaveRequests->get();

            $data = new DailyLeaveCollection($leaveRequests);
            return $this->responseWithSuccess('Users total leave requests', $data, 200);
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage(), [], 400);
        }
    }



 public function getStatistic($leaveRequests){
    $result['approved']['early_leave'] = $leaveRequests->where('leave_type', 'early_leave')
    ->where('approved_at_hr', '!=', null)
    ->count();

    $result['approved']['late_arrive'] = $leaveRequests->where('leave_type', 'late_arrive')
    ->where('approved_at_hr', '!=', null)
    ->count();

    $result['rejected']['early_leave'] = $leaveRequests->where('leave_type', 'early_leave')
    ->where('rejected_at_hr', '!=', null)
    ->count();

    $result['rejected']['late_arrive'] = $leaveRequests->where('leave_type', 'late_arrive')
    ->where('rejected_at_hr', '!=', null)
    ->count();

    $result['pending']['early_leave'] = $leaveRequests->where('leave_type', 'early_leave')
    ->where('approved_at_hr',null)
    ->where('rejected_at_hr',null)
    ->count();

    $result['pending']['late_arrive'] = $leaveRequests->where('leave_type', 'late_arrive')
    ->where('approved_at_hr',null)
    ->where('rejected_at_hr',null)
    ->count();

    return $result;
 }

    //monthlySummeryView function
    public function monthlySummeryView( $request)
    {
        try {
            if(@$request['month']){
                $month = $request['month'];
            }else{
                $month = date('Y-m');
            }
            if(@$request['user_id']){
                $userId = $request['user_id'];
            }else{
                $userId = auth()->id();
            }

            $getUserIds[] =$userId;

            $leaveRequests = DailyLeave::query();

            if($month){
                $leaveRequests = $leaveRequests->where('date', 'LIKE', '%'. $month . '%');
            }
            if(isset($request['date'])){
                $leaveRequests = $leaveRequests->where('date',$request['date']);
            }

            if($userId){
                $leaveRequests = $leaveRequests->where('user_id', $userId);
            }


            $leaveRequests = $leaveRequests->get();

            // month date format
            $month_date = date('F Y', strtotime($month));
            $message = 'Monthly summery of ' .  $month_date ;


            $result = $this->getStatistic($leaveRequests);

            return $this->responseWithSuccess($message, $result, 200);
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage(), [], 400);
        }
    }

    public function dateWiseLeaveCount($date)
    {
        try {
            $leaveRequests = $this->leaveRequest->query()
            ->where('company_id',$this->companyInformation()->id)
            ->where(function($query) use($date) {
                $query->where('leave_from', 'LIKE', '%'. $date . '%')
                ->orWhere('leave_to', 'LIKE', '%'. $date . '%');
            })
            ->count();
            return $leaveRequests;
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage(), [], 400);
        }
    }

    public function LeaveView($request)
    {
        $validator = Validator::make(request()->all(), [
            'leave_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError($validator->errors()->first(), [], 400);
        }
        try {

            $leaveRequests=DailyLeave::where('id',$request->leave_id)->first();
            $data = [
                'id' => $leaveRequests->id,
                'date' => $this->dateFormatWithoutTime($leaveRequests->date),
                'staff' => @$leaveRequests->user->name,
                'leave_type' => str_replace('_', ' ',Str::title($leaveRequests->leave_type)),
                'time' => $this->timeFormatInPlainText($leaveRequests->time),
                'reason' => @$leaveRequests->reason,
                'approval_details'=> [
                    'manager_approval' => $leaveRequests->approved_at_tl!=""? @$leaveRequests->tlApprovedBy->name .' Approved at '. @$this->dateFormatInPlainText($leaveRequests->approved_at_tl):null,
                    'hr_approval' => $leaveRequests->approved_at_hr!=""? @$leaveRequests->hrApprovedBy->name .' Approved at '. @$this->dateFormatInPlainText($leaveRequests->approved_at_hr):null,
                ],
                'tl_approval_msg' => $leaveRequests->approved_at_tl!=""? 'TL Approved at '. @$this->dateFormatInPlainText($leaveRequests->approved_at_tl):'TL Not Approved Yet',
                'status'=>  ($leaveRequests->approved_at_tl !=""  ||  $leaveRequests->approved_at_hr != "")  ? 'Approved' : 'Pending',
            ];;
            return $this->responseWithSuccess('Users total leave requests', $data, 200);
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage(), [], 400);
        }
    }

      // new functions 

      function fields()
      {
          return [
              _trans('common.ID'),
              _trans('common.Name'),
              _trans('common.Leave Type'),
              _trans('common.Date'),
              _trans('common.Days'),
              _trans('common.Available Days'),
              _trans('common.Substitute'),
              _trans('common.Manager Approved'),
              _trans('common.HR Approved'),
              _trans('common.File'),
              _trans('common.Status'),
              _trans('common.Action')
  
          ];
      }

    function table($request)
    { 
            
            $leaveRequest = $this->leaveRequest->query()->where('company_id', auth()->user()->company_id);
            if (auth()->user()->role->slug == 'staff') {
                $leaveRequest = $leaveRequest->where('user_id', auth()->id());
            } else {
                $leaveRequest->when(\request()->get('user_id'), function (Builder $builder) {
                    return $builder->where('user_id', \request()->get('user_id'));
                });
            }
            if ($request->from && $request->to) {
                $leaveRequest = $leaveRequest->whereBetween('apply_date', start_end_datetime($request->from, $request->to));
            }
            $leaveRequest->when(\request()->get('type'), function (Builder $builder) {
                return $builder->where('status_id', \request()->get('type'));
            });
            
            $data = $leaveRequest->orderBy('id','desc')->paginate($request->limit ?? 2);
            return [
                'data' => $data->map(function ($data) {
                    $action_button = '';
                    $view = _trans('common.View');
                    $approve = _trans('common.Approve');
                    $reject = _trans('common.Reject');
                    $refer = _trans('common.Refer');
                    $delete = _trans('common.Delete');
                    if (hasPermission('leave_request_read')) {
                        $action_button .= '<a href="' . route('leaveRequest.view', $data->id) . '" class="dropdown-item">  <span class="icon mr-8"><i class="fa-regular fa-eye"></i></span>'. $view .' </a>';
                    }
    
                    if (hasPermission('leave_request_approve')) {
                        if ($data->status_id == 6 || $data->status_id == 17) {
                            $action_button .= actionButton($approve, 'ApproveOrReject(' . $data->id . ',' . "1" . ',`hrm/leave/request/approve-or-reject/`,`Approve`)', 'approve');
                        }
                        if ($data->status_id == 2) {
                            $action_button .= actionButton($approve, 'ApproveOrReject(' . $data->id . ',' . "1" . ',`hrm/leave/request/approve-or-reject/`,`Approve`)', 'approve');
                            $action_button .= actionButton($reject, 'ApproveOrReject(' . $data->id . ',' . "6" . ',`hrm/leave/request/approve-or-reject/`,`Reject`)', 'reject');
                        }
                    }
                    
                    if (hasPermission('leave_request_approve')) {
                        if ($data->status_id == 1) {
                            $action_button .= actionButton($reject, 'ApproveOrReject(' . $data->id . ',' . "6" . ',`hrm/leave/request/approve-or-reject/`,`Reject`)', 'reject');
                        }
                    }
    
                    if (hasPermission('leave_request_delete')) {
                        $action_button .= actionButton($delete, '__globalDelete(' . $data->id . ',`hrm/leave/request/delete/`)', 'delete');
                    }
                    if (hasPermission('team_update')) {
                        if ($data->status_id != 17) {
                            $action_button .= actionButton($refer, 'ApproveOrReject(' . $data->id . ',' . "17" . ',`hrm/leave/request/approve-or-reject/`,`Refer`)', 'approve');
                        }
                    }
                    $button = ' <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                    ' . $action_button . '
                                    </ul>
                                </div>';

                    return [
                        
                        'id'         => $data->id,
                        'file'       => ($data->attachment_file_id !=null) ?  '<a href="' . uploaded_asset($data->attachment_file_id) . '" download class="btn btn-white btn-sm"><i class="fas fa-download"></i></a>' : _trans('common.No File'),
                        'name'       => $data->user->name,
                        'type'       => @$data->assignLeave->type->name,
                        'date'       => $data->leave_from,
                        'days'       => $data->days,
                        'available_days' => @ resolve(LeaveService::class)->leaveSummary()->original['data']['leave_balance'] ?? 0,
                        'substitute' => @$data->substitute->name,
                        'manager_approved' => @AuthorInfo::where(['authorable_type' => get_class($this->leaveRequest), 'authorable_id' => $data->id])->first()->referredUser->name ? _trans('common.Yes') : _trans('attendance.Pending'),
                        'hr_approved' => @AuthorInfo::where(['authorable_type' => get_class($this->leaveRequest), 'authorable_id' => $data->id])->first()->approveUser->name ? _trans('common.Yes') : _trans('attendance.Pending'),
                        'status'     => '<small class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</small>',
                        'action'     => $button
                    ];
                }),
                'pagination' => [
                    'total' => $data->total(),
                    'count' => $data->count(),
                    'per_page' => $data->perPage(),
                    'current_page' => $data->currentPage(),
                    'total_pages' => $data->lastPage(),
                    'pagination_html' =>  $data->links('backend.pagination.custom')->toHtml(),
                ],
            ];
    }


    // statusUpdate
    public function statusUpdate($request)
    {
        try {
            
            if (@$request->action == 'active') {
                $leave_request = $this->leaveRequest->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Leave request activate successfully.'), $leave_request);
            }
            if (@$request->action == 'inactive') {
                $leave_request = $this->leaveRequest->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Leave request inactivate successfully.'), $leave_request);
            }
            return $this->responseWithError(_trans('message.Leave request inactivate failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $leave_request = $this->leaveRequest->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                return $this->responseWithSuccess(_trans('message.Leave request delete successfully.'), $leave_request);
            } else {
                return $this->responseWithError(_trans('message.Leave request inactivate failed'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

}
