<?php

namespace App\Repositories\Hrm\Notice;

use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Upload;
use App\Models\Hrm\Notice\Notice;
use Illuminate\Support\Facades\DB;
use App\Models\Expenses\HrmExpense;
use Illuminate\Support\Facades\Log;
use App\Services\Hrm\ExpenseService;
use Illuminate\Support\Facades\Auth;
use App\Models\Expenses\ExpenseClaim;
use App\Models\Expenses\PaymentHistory;
use App\Models\Expenses\IncomeExpenseCategory;
use App\Models\Hrm\Notice\NoticeViewLog;
use App\Models\Hrm\Department\Department;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Models\Hrm\Notice\NoticeDepartment;
use App\Http\Resources\Hrm\NoticeCollection;
use App\Models\Expenses\ExpenseClaimDetails;
use App\Http\Resources\Hrm\ExpenseCollection;
use App\Http\Resources\Hrm\PaymentCollection;
use App\Http\Resources\Hrm\ClaimHistoryCollection;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\FirebaseNotification;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Department\DepartmentRepository;

class NoticeRepository
{
    use RelationshipTrait, ApiReturnFormatTrait, FileHandler, DateHandler, FirebaseNotification;

    protected $notice;
    protected $noticeViewLog;
    protected $departmentRepo;

    public function __construct(Notice $notice, NoticeViewLog $noticeViewLog, DepartmentRepository $departmentRepo)
    {
        $this->notice = $notice;
        $this->noticeViewLog = $noticeViewLog;
        $this->departmentRepo = $departmentRepo;
    }

    public function noticeList($request)
    {


        $validator = Validator::make($request->all(), [
            'month' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Required field missing'), $validator->errors(), 422);
        }

        $noticeIds = $this->noticeViewLog->query()->where(['user_id' => auth()->user()->id, 'is_view' => 0])->orderBy('id', 'desc')->pluck('notice_id');
        $month = $this->onlyMonth($request->month);
        $notices = $this->notice->query()
            ->with('noticeDepartments')
            ->where('notices.company_id', $this->companyInformation()->id)
            ->Join('notice_departments', 'notice_departments.noticeable_id', '=', 'notices.id')
            ->where('notice_departments.department_id', auth()->user()->department_id)
            ->whereMonth('date', $month)
            ->orderBy('notices.id', 'DESC')
            ->select('notices.*')
            ->get();
        $data['notices'] = new NoticeCollection($notices);
        return $this->responseWithSuccess('Notice List', $data, 200);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $notice = $this->notice->query()->where('id', $id)->first();

        $data['id'] = $notice->id;
        $data['subject'] = $notice->subject;
        $data['description'] = $notice->description;
        $data['date'] = $this->dateFormatInPlainText($notice->created_at);
        $data['file'] = $notice->attachment_file_id ? uploaded_asset($notice->attachment_file_id) : null;


        if ($data) {
            return $this->responseWithSuccess('Notice Details', $data, 200);
        } else {
            return $this->responseWithError('No data found', [], 400);
        }
    }

    public function clearNotice(): \Illuminate\Http\JsonResponse
    {
        $noticeIds = $this->noticeViewLog->query()->where(['user_id' => auth()->id(), 'is_view' => 0])->update([
            'is_view' => 1
        ]);
        return $this->responseWithSuccess('Notice cleared', [], 200);
    }


    public function store($request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'department_id' => 'required',
            'file' => 'sometimes|max:2048',
            'subject' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {


            $notice = new $this->notice;
            $notice->created_by = Auth::user()->id;
            $notice->company_id = $this->companyInformation()->id;
            $notice->subject = $request->subject;
            $notice->date = $request->date;
            $notice->description = $request->description;
            if ($request->hasFile('file')) {
                $notice->attachment_file_id = $this->uploadImage($request->file)->id;
            }
            $notice->save();

            foreach ($request->department_id as $key => $item) {
                $department = Department::with('users')->where('id', $item)->first();
                foreach ($department->users as $user) {
                    $details = [
                        'title' => $notice->subject,
                        'body' => $notice->description,
                        'actionText' => 'View',
                        'actionURL' => [
                            'app' => 'notice',
                            'web' => '',
                            'target' => '_blank',
                        ],
                        'sender_id' => auth()->id()
                    ];

                    sendDatabaseNotification($user, $details);

                    $this->sendCustomFirebaseNotification($user->id, 'notice', '', 'notice', $request->subject, $request->description);
                }

                $departmentNotice = new NoticeDepartment;
                $departmentNotice->department_id = $item;
                $departmentNotice->noticeable_id = $notice->id;
                $departmentNotice->noticeable_type = 'App\Models\Hrm\Notice';
                $departmentNotice->save();
            }

            return $this->responseWithSuccess('Notice created successfully', 200);
        } catch (\Throwable $th) {
            return $this->responseWithError('Server error', [], 500);
        }
    }

    public function storeNotice($request)
    {
        $validator = Validator::make($request->all(), [
            'department_id' => 'required',
            'file' => 'sometimes|max:2048',
            'subject' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            //string to array
            $request['department_id'] = explode(',', $request['department_id']);

            $notice = new $this->notice;
            $notice->created_by = Auth::user()->id;
            $notice->company_id = $this->companyInformation()->id;
            $notice->subject = $request->subject;
            $notice->date = $request->date;
            $notice->description = $request->description;
            if ($request->hasFile('file')) {
                $notice->attachment_file_id = $this->uploadImage($request->file)->id;
            }
            $notice->save();

            foreach ($request->department_id as $key => $item) {
                $department = Department::with('users')->where('id', $item)->first();
                foreach ($department->users as $user) {
                    $this->sendCustomFirebaseNotification($user->id, 'notice', '', 'notice', $request->subject, $request->description);
                }

                $departmentNotice = new NoticeDepartment;
                $departmentNotice->department_id = $item;
                $departmentNotice->noticeable_id = $notice->id;
                $departmentNotice->noticeable_type = 'App\Models\Hrm\Notice';
                $departmentNotice->save();
            }

            return $this->responseWithSuccess('Notice created successfully', 200);
        } catch (\Throwable $th) {
            return $this->responseWithError('Server error', [], 500);
        }
    }

    public function update($request)
    {
        $validator = Validator::make($request->all(), [
            'department_id' => 'required',
            'file' => 'sometimes|max:2048',
            'subject' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {


            $notice = $this->notice->where('id', $request->notice_id)->first();
            $notice->created_by = Auth::user()->id;
            $notice->subject = $request->subject;
            $notice->date = $request->date;
            $notice->description = $request->description;
            if ($request->hasFile('file')) {
                $notice->attachment_file_id = $this->uploadImage($request->file)->id;
            }
            $notice->save();
            $notice->noticeDepartments()->delete();

            foreach ($request->department_id as $key => $item) {
                $department = Department::with('users')->where('id', $item)->first();
                foreach ($department->users as $user) {
                    $this->sendCustomFirebaseNotification($user->id, 'notice', '', 'notice', $request->subject, $request->description);
                }

                $departmentNotice = new NoticeDepartment;
                $departmentNotice->department_id = $item;
                $departmentNotice->noticeable_id = $notice->id;
                $departmentNotice->noticeable_type = 'App\Models\Hrm\Notice';
                $departmentNotice->save();
            }

            return $this->responseWithSuccess('Notice Updated successfully', 200);
        } catch (\Throwable $th) {
            return $this->responseWithError('Server error', [], 500);
        }
    }


    public function expenseUpdate($request, $id): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'expense_category_id' => 'required',
            'attachment_file' => 'sometimes|max:2048',
            'amount' => 'required|numeric',
            'remarks' => 'sometimes|max:255',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }

        try {
            $data = $this->service->model->query()->find($id);
            if ($data) {
                if ($data->is_claimed_status_id == 11) {
                    $data->income_expense_category_id = $request->expense_category_id;
                    $data->remarks = $request->remarks;
                    $data->amount = $request->amount;
                    if ($request->hasFile('attachment_file')) {
                        $request['attachment_file_id'] = $this->uploadImage($request->attachment_file, 'expense')->id;
                        $data->attachment_file_id = $request->attachment_file_id;
                    }
                    $data->save();
                    return $this->responseWithSuccess('Expense updated successfully', [], 200);
                } else {
                    return $this->responseWithError('Already claimed this expense', [], 400);
                }
            } else {
                return $this->responseWithError('No data found', [], 400);
            }
        } catch (\Throwable $throwable) {
            return $this->responseWithError('Server error', [], 500);
        }
    }

    public function claimSend($request)
    {
        $validator = Validator::make($request->all(), [
            'remarks' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        //is_claimed_status_id = 10 claimed
        //is_claimed_status_id = 11 not claimed yet
        //status_id = 9 unpaid
        DB::beginTransaction();
        try {
            $nonClaimedExpenses = $this->service->model->query()
                ->where(['company_id' => $this->companyInformation()->id, 'is_claimed_status_id' => 11, 'user_id' => auth()->id()])
                ->get();

            if ($nonClaimedExpenses->count() > 0) {
                $claim = new ExpenseClaim;
                $claim->company_id = $this->companyInformation()->id;
                $claim->user_id = auth()->id();
                $claim->invoice_number = 'HRM-' . rand(10000, 90000);
                $claim->claim_date = date('Y-m-d');
                $claim->remarks = $request->remarks;
                $claim->payable_amount = $nonClaimedExpenses->sum('amount');
                $claim->due_amount = $nonClaimedExpenses->sum('amount');
                $claim->status_id = 9;
                $claim->save();
                if ($claim) {
                    foreach ($nonClaimedExpenses as $expense) {
                        $claimDetails = new ExpenseClaimDetails;
                        $claimDetails->hrm_expense_id = $expense->id;
                        $claimDetails->company_id = $claim->company_id;
                        $claimDetails->user_id = $claim->user_id;
                        $claimDetails->amount = $expense->amount;
                        $claimDetails->save();

                        $e = HrmExpense::find($expense->id);
                        $e->is_claimed_status_id = 10;
                        $e->save();
                    }
                }
            } else {
                return $this->responseWithError('No claimed created', [], 400);
            }
            DB::commit();
            return $this->responseWithSuccess('Claimed successfully', [], 200);
        } catch (\Throwable $throwable) {
            DB::rollBack();
        }
    }

    public function claimHistory($request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        $month = Carbon::parse($request->month)->format('m');
        $year = Carbon::parse($request->month)->format('Y');
        $claimHistory = ExpenseClaim::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('company_id', $this->companyInformation()->id)
            ->where('user_id', auth()->id())
            ->paginate(15);
        $data = new ClaimHistoryCollection($claimHistory);
        return $this->responseWithSuccess('Claim history', $data, 200);
    }

    public function paymentHistory($request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }

        $month = Carbon::parse($request->month)->format('m');
        $year = Carbon::parse($request->month)->format('Y');
        $paymentHistory = PaymentHistory::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('company_id', $this->companyInformation()->id)
            ->where('user_id', auth()->id())
            ->paginate(15);
        $data = new PaymentCollection($paymentHistory);
        return $this->responseWithSuccess('Claim history', $data, 200);
    }

    public function claimDetails($id)
    {
        $claim = $this->expenseClaim->query()->find($id);
        if ($claim) {

            $data['invoice_number'] = $claim->invoice_number;
            $data['claim_date'] = $claim->claim_date;
            $data['remarks'] = $claim->remarks;
            $data['amount'] = $claim->payable_amount;
            $data['attachment_file'] = uploaded_asset($claim->attachment_file_id);
            return $this->responseWithSuccess('Claim details', $data, 200);
        } else {
            return $this->responseWithError('No data found', [], 400);
        }
    }

    public function destroy($notice)
    {
        $notice->noticeDepartments()->delete();
        return $notice->delete();
    }

    public function staffNoticeDatatable($request)
    {

        $notice = $this->notice->with('noticeDepartments')
            ->whereHas('noticeDepartments', function ($query) {
                $query->where('department_id', auth()->user()->department_id);
            });
        $notice = $notice->orderBy('id', 'desc');

        return $this->datatable($notice);
    }
    public function noticeDatatable($request)
    {


        $notice = $this->notice->with('noticeDepartments');

        $notice = $notice->orderBy('id', 'desc');

        return $this->datatable($notice);
    }
    public function datatable($notice)
    {
        return datatables()->of($notice->latest()->get())
            ->addColumn('subject', function ($data) {
                return @$data->subject;
            })
            ->addColumn('date', function ($data) {
                return showDate($data->date);
            })
            ->addColumn('department', function ($data) {
                $department_title = [];
                foreach ($data->noticeDepartments as $key => $noticeDepartment) {
                    $department_title[] = $noticeDepartment->department->title;
                }
                return @implode(", ", $department_title);
            })
            ->addColumn('description', function ($data) {
                return @$data->description;
            })
            ->addColumn('file', function ($data) {
                if ($data->attachment_file_id != null) {
                    // uploaded asset with type function is defined in Helpers.php file
                    $uploaded = uploaded_asset_with_type($data->attachment_file_id);
                    if ($uploaded['type'] == 'pdf') {
                        return '<a href="' . url('assets/download/' . $data->attachment_file_id) . '" target="_blank"> <i class="fas fa-file-pdf"></i> </a>';
                    } else {
                        $upload_path = uploaded_asset($data->attachment_file_id);
                        return '<a href="' . url('assets/download/' . $data->attachment_file_id) . '" target="_blank"> <img height="40px" width="40px" src="' . url($upload_path) . '"/> </a>';
                    }
                } else {
                    return 'No file';
                }
            })
            ->addColumn('action', function ($data) {
                $action_button = '';
                if (hasPermission('notice_update')) {
                    $action_button .= '<a href="' . route('notice.edit', $data->id) . '" class="dropdown-item"> Edit</a>';
                }
                if (hasPermission('designation_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`dashboard/announcement/notice/delete/`)', 'delete');
                }

                $button = '<div class="flex-nowrap">
                <div class="dropdown">
                    <button class="btn btn-white dropdown-toggle align-text-top action-dot-btn" data-boundary="viewport" data-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">' . $action_button . '</div>
                </div>
            </div>';
                return $button;
            })
            ->rawColumns(array('subject', 'date', 'department', 'description', 'file', 'action'))
            ->make(true);
    }

    // new functions

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Date'),
            _trans('common.Subject'),
            _trans('common.Department'),
            _trans('common.Description'),
            _trans('common.File'),
            _trans('common.Action')
        ];
    }

    function table($request)
    {
        
        $data = $this->notice->query()->where('company_id', auth()->user()->company_id);
        if ($request->from && $request->to) {
            $data = $data->whereBetween('date', start_end_datetime($request->from, $request->to));
        }
        if ($request->department) {
            $data = $data->whereHas('noticeDepartments', function ($query) use ($request) {
                $query->where('department_id', $request->department);
            });
        }

        if ($request->search) {
            $data = $data->where('subject', 'like', '%' . $request->search . '%');
        }
        $data = $data->orderBy('id', 'desc')->paginate(10);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('notice_update')) {
                    $action_button .= '<a href="' . route('notice.edit', $data->id) . '" class="dropdown-item"> <span class="icon mr-8"><i class="fa-solid fa-pen-to-square"></i></span> Edit</a>';
                }
                if (hasPermission('designation_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`dashboard/announcement/notice/delete/`)', 'delete');
                }

                $file = _trans('common.No File');
                if ($data->attachment_file_id != null) {
                    $uploaded = uploaded_asset_with_type($data->attachment_file_id);
                    if ($uploaded['type'] == 'pdf') {
                        $file = '<a href="' . url('assets/download/' . $data->attachment_file_id) . '" target="_blank"> <i class="fas fa-file-pdf"></i> </a>';
                    } else {
                        $upload_path = uploaded_asset($data->attachment_file_id);
                        $file = '<a href="' . url('assets/download/' . $data->attachment_file_id) . '" target="_blank"> <img height="40px" width="40px" src="' . url($upload_path) . '"/> </a>';
                    }
                }

                return [
                    'id'            => $data->id,
                    'date'  => showDate($data->date),
                    'subject'       => $data->subject,
                    'department'    => $data->noticeDepartments->map(function ($noticeDepartment) {
                        return $noticeDepartment->department->title;
                    })->implode(', '),

                    'description'   => '<button  data-toggle="tooltip" data-placement="top" title="' . $data->description . '">
                    "' . \Illuminate\Support\Str::limit($data->description, 50, $end = '...') . '"
                  </button>',
                    'file'       => @$file,
                    'action'     => actionHTML($action_button)
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
    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $notices = $this->notice->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->get();
                foreach ($notices as $notice) {
                    if (@$notice->attachment_file_id) {
                        $this->deleteImage(asset_path($notice->attachment_file_id));
                    }
                    $notice->noticeDepartments()->delete();
                    $notice->delete();
                }
                return $this->responseWithSuccess(_trans('message.Notice delete successfully.'), []);
            } else {
                return $this->responseWithError(_trans('message.Notice not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
