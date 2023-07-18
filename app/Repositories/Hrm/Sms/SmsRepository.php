<?php

namespace App\Repositories\Hrm\Sms;

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
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Http\Resources\Hrm\NoticeCollection;
use App\Models\Expenses\ExpenseClaimDetails;
use App\Http\Resources\Hrm\ExpenseCollection;
use App\Http\Resources\Hrm\PaymentCollection;
use App\Http\Resources\Hrm\ClaimHistoryCollection;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Models\SmsLog;

class SmsRepository
{
    use RelationshipTrait, ApiReturnFormatTrait, FileHandler, DateHandler;

    protected $notice;


    public function __construct(SmsLog $smsLog)
    {
        $this->smsLog = $smsLog;
    }

    public function noticeList($request)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Required field missing'), $validator->errors(), 422);
        }
        $month = $this->onlyMonth($request->month);

        $noticeIds = $this->noticeViewLog->query()->where(['user_id' => auth()->user()->id, 'is_view' => 0])->orderBy('id', 'desc')->pluck('notice_id');
        $notices = $this->notice->query()->whereIn('id', $noticeIds)->orderBy('id', 'desc')->whereMonth('created_at', $month)->get();
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
        $data['file'] = uploaded_asset($notice->attachment_file_id);

        if ($data) {
            return $this->responseWithSuccess('Notice Details', $data, 200);
        } else {
            return $this->responseWithError('No data found', [], 400);
        }
    }

    public function clearNotice()
    {
        $noticeIds = $this->noticeViewLog->query()->where(['user_id' => auth()->id(), 'is_view' => 0])->update([
            'is_view' => 1
        ]);
        return $this->responseWithSuccess('Notice cleared', [], 200);
    }


    public function store($request)
    {
        foreach ($request->department_id as $key => $department) {
            $users=User::where('department_id',$department)->get();
            foreach ($users as $user) {
                $this->smsLog->query()->create([
                    'receiver_number' => $user->phone,
                    'message' => $request->message,
                ]);
            }

        }
        return true;
    }

    public function storeNotice($request)
    {
        if ($request->hasFile('file')) {
            $attachment_file_id = $this->uploadImage($request->file)->id;
        }

        foreach ($request->department_id as $key => $item) {
            $this->notice->query()->create([
                'created_by' => Auth::user()->id,
                'company_id' => $this->companyInformation()->id,
                'department_id' => $item,
                'attachment_file_id' => $attachment_file_id,
                'subject' => $request->subject,
                'date' => $request->date,
                'description' => $request->description,
            ]);
        }
        return true;
    }

    public function update($request)
    {
        $notice = $this->notice->where('id', $request->notice_id)->first();
        $notice->subject = $request->subject;
        $notice->description = $request->description;
        $notice->company_id = $this->companyInformation()->id;
        $notice->department_id = $request->department_id;
        $notice->date = $request->date;
        $notice->status_id = $request->status_id;
        if ($request->hasFile('file')) {
            $attachment_file_id = $this->uploadImage($request->file)->id;
            $notice->attachment_file_id = $attachment_file_id;
        }
        $notice->save();
        return true;
    }


    public function expenseUpdate($request, $id)
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

    public function claimHistory($request)
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

    public function paymentHistory($request)
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
        return $notice->delete();
    }

    public function noticeDatatable($request)
    {

        $notice = $this->notice;
        if ($request->has('company_id')) {
            $company_id = $request->company_id;
            $notice = $notice->where('company_id', $company_id);
        }
        if ($request->has('department_id')) {
            $department_id = $request->department_id;
            $notice = $notice->where('department_id', $department_id);
        }
        if ($request->has('user_id')) {
            $user_id = $request->user_id;
            $notice = $notice->where('user_id', $user_id);
        }



        $noticeIds = $this->noticeViewLog->query();
        if ($request->has('user_id')) {
            $user_id = $request->user_id;
            $noticeIds = $noticeIds->where('user_id', $user_id);
            $noticeIds = $noticeIds->where('is_view', 0);
            $noticeIds = $noticeIds->orderBy('id', 'desc')->pluck('notice_id');
            $notice = $this->notice->query()->whereIn('id', $noticeIds);
        }
        $notice = $notice->orderBy('id', 'desc');


        if (@$request->daterange) {
            $dateRange = explode(' - ', $request->daterange);
            $from = date('Y-m-d', strtotime($dateRange[0]));
            $to = date('Y-m-d', strtotime($dateRange[1]));
            $notice = $notice->whereBetween('date', start_end_datetime($from, $to));
        }
        return datatables()->of($notice->latest()->get())
            ->addColumn('subject', function ($data) {
                return @$data->subject;
            })
            ->addColumn('date', function ($data) {
                return showDate($data->date);
            })
            ->addColumn('department', function ($data) {
                return @$data->department->title;
            })
            ->addColumn('description', function ($data) {
                return @$data->description;
            })
            ->addColumn('file', function ($data) {
                if ($data->attachment_file_id != null) {
                    // uploaded asset with type function is defined in Helpers.php file
                    $uploaded = uploaded_asset_with_type($data->attachment_file_id);
                    if ($uploaded['type'] == 'pdf') {
                        return '<a href="'.url('assets/download/'. $data->attachment_file_id).'" target="_blank"> <i class="fas fa-file-pdf"></i> </a>';
                    } else {
                        $upload_path = 'public' . uploaded_asset($data->attachment_file_id);
                        return '<a href="'.url('assets/download/'. $data->attachment_file_id).'" target="_blank"> <img height="40px" width="40px" src="'.url($upload_path ) . '"/> </a>';
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


            ->rawColumns(array('subject', 'date', 'department',  'description', 'file', 'action'))
            ->make(true);
    }
}
