<?php

namespace  App\Repositories\Hrm\Finance;

use Carbon\Carbon;
use App\Models\Finance\Account;
use App\Models\Finance\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Finance\Transaction;
use Illuminate\Support\Facades\Log;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Http\Resources\Hrm\Accounts\ExpenseCollection;
use App\Http\Resources\Hrm\Accounts\ExpenseDetailsCollection;

class ExpenseRepository
{

    use ApiReturnFormatTrait, FileHandler;
    protected $model;

    public function __construct(Expense $model)
    {
        $this->model = $model;
    }


    public function model($filter = null)
    {
        $model = $this->model;
        if ($filter) {
            $model = $this->model->where($filter);
        }
        return $model;
    }

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('account.Employee'),
            _trans('account.Category'),
            _trans('account.Amount'),
            _trans('account.Date'),
            _trans('account.Payment'),
            _trans('account.Status'),
            _trans('account.Action'),
        ];
    }
    function UserExpenseList($request)
    {
        $params = [];
        $content = $this->model->query()->with('user:id,name', 'category:id,name', 'payment:id,name,class', 'status:id,name,class')
            ->where('user_id', auth()->user()->id)
            ->select('company_id', 'date', 'pay', 'status_id', 'income_expense_category_id', 'id', 'user_id', 'created_at', 'amount', 'request_amount');
        // if($request->from_date && $request->to_date = null){
        //     $content = $content->where('created_at','LIKE %', date('Y-m-d', strtotime($request->from_date)));
        // }
        if ($request->date) {
            $rawDate = explode('-', $request->date);
            $content = $content->whereBetween('created_at', start_end_datetime(date('Y-m-d', strtotime($rawDate[0])), date('Y-m-d', strtotime($rawDate[1]))));
        }
        if ($request->payment) {
            $params['pay'] = $request->payment;
        }
        if ($request->status) {
            $params['status_id'] = $request->status;
        }
        $content = $content->where($params)->get();
        return new ExpenseCollection($content);
    }
    function UserExpenseView($request, $expense_id)
    {

        try {
            $expense_info = $this->model([
                'id' => $expense_id,
            ])->with('user:id,name', 'category:id,name', 'payment:id,name,class', 'status:id,name,class')->first();
            if ($expense_info) {
                $data = [];
                $data['id'] = $expense_info->id;
                $data['category'] = $expense_info->category->name;
                $data['requested_amount'] = currency_format($expense_info->request_amount);
                $data['approved_amount'] = currency_format($expense_info->amount);
                $data['date_show'] = showDate($expense_info->created_at->format('d-m-Y'));
                $data['date_db'] = $expense_info->created_at->format('d-m-Y');
                $data['payment'] = plain_text($expense_info->payment->name);
                $data['status'] = plain_text($expense_info->status->name);
                $data['reason'] = @$expense_info->remarks;
                return $this->responseWithSuccess('Expense Details', $data, 200);
            } else {
                $data = [];
                return $this->responseWithSuccess('Expense Not Found', $data, 404);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError(_trans('response.Something went wrong'), '', 500);
        }
    }

    public function datatable($request)
    {
        $params = [];

        $content = $this->model->query()->with('user:id,name', 'category:id,name', 'payment:id,name,class', 'status:id,name,class')->where('company_id', auth()->user()->company_id)
            ->select('company_id', 'date', 'pay', 'status_id', 'income_expense_category_id', 'id', 'user_id', 'created_at', 'amount', 'request_amount');
        if ($request->date) {
            $rawDate = explode('-', $request->date);
            $content = $content->whereBetween('created_at', start_end_datetime(date('Y-m-d', strtotime($rawDate[0])), date('Y-m-d', strtotime($rawDate[1]))));
        }
        if ($request->payment) {
            $params['pay'] = $request->payment;
        }
        if ($request->status) {
            $params['status_id'] = $request->status;
        }
        $content = $content->where($params);
        return datatables()->of($content->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';

                if (hasPermission('expense_view')) {
                    $action_button .= '<a href="' . route('hrm.expenses.show', $data->id) . '" class="dropdown-item"> ' . _trans('common.View') . '</a>';
                }

                if (hasPermission('expense_edit') && @$data->pay == 9) {
                    $action_button .= '<a href="' . route('hrm.expenses.edit', $data->id) . '" class="dropdown-item"> ' . _trans('common.Edit') . '</a>';
                }
                if (hasPermission('expense_approve') && @$data->pay == 9) {
                    $action_button .= actionButton(_trans('common.Approve'), 'mainModalOpen(`' . route('hrm.expenses.approve_modal', $data->id) . '`)', 'modal');
                }

                if (hasPermission('expense_pay') && $data->status_id == 5 && @$data->pay == 9) {
                    $action_button .= actionButton(_trans('common.Pay'), 'mainModalOpen(`' . route('hrm.expenses.pay', $data->id) . '`)', 'modal');
                }
             

                if (hasPermission('expense_delete') && $data->status_id != 5 && @$data->pay == 9) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/expenses/delete/`)', 'delete');
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
            ->addColumn('employee', function ($data) {
                return @$data->user->name;
            })
            ->addColumn('category', function ($data) {
                return @$data->category->name;
            })
            ->addColumn('payment', function ($data) {
                return '<small class="badge badge-' . @$data->payment->class . '">' . @$data->payment->name . '</small>';
            })
            ->addColumn('date', function ($data) {
                return @$data->created_at->format('d-m-Y');
            })
            ->addColumn('status', function ($data) {
                return '<small class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</small>';
            })
            ->addColumn('amount', function ($data) {
                $amount = '';
                $amount .= '<small class="text-info">' . _trans('payroll.Requested') . ' : ' . currency_format($data->request_amount) . '</small><br>';
                $amount .= '<small class="text-success">' . _trans('payroll.Approved') . ' : ' . currency_format($data->amount ?? 0) . '</small> <br>';
                return $amount;
            })
            ->rawColumns(array('account', 'category', 'amount', 'date', 'payment', 'action', 'status'))
            ->make(true);
    }


    public function store($request)
    {

        DB::beginTransaction();
        try {

            $expense                                 = new $this->model;
            $expense->user_id                        = auth()->id();
            $expense->income_expense_category_id     = $request->category;
            $expense->company_id                     = auth()->user()->company->id;
            $expense->date                           = $request->date;
            $expense->amount                         = $request->amount;
            $expense->request_amount                 = $request->amount;
            $expense->ref                            = $request->ref;
            $expense->remarks                        = $request->description;
            $expense->created_by                     = auth()->id();
            $expense->updated_by                     = auth()->id();
            if ($request->hasFile('attachment')) {
                $expense->attachment                 = $this->uploadImage($request->attachment, 'expense')->id;
            }
            $expense->save();

            DB::commit();

            return $this->responseWithSuccess(_trans('message.Expense created successfully.'), $expense);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }
    public function update($request, $expense)
    {

        DB::beginTransaction();
        try {

            $expense->income_expense_category_id     = $request->category;
            $expense->date                           = $request->date;
            $expense->amount                         = $request->amount;
            $expense->request_amount                 = $request->amount;
            $expense->ref                            = $request->ref;
            $expense->remarks                        = $request->description;
            $expense->updated_by                     = auth()->id();
            if ($request->hasFile('attachment')) {
                if ($expense->attachment) {
                    $this->deleteImage(asset_path($expense->attachment));
                    $expense->attachmentFile->delete();
                }
                $expense->attachment                 = $this->uploadImage($request->attachment, 'expense')->id;
            }
            $expense->save();

            DB::commit();

            return $this->responseWithSuccess(_trans('message.Expense Update successfully.'), $expense);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    function delete($expense)
    {
        DB::beginTransaction();
        try {
            if ($expense->attachment) {
                $this->deleteImage(asset_path($expense->attachment));
            }
            $expense->attachmentFile->delete();
            $expense->delete();

            DB::commit();

            return $this->responseWithSuccess(_trans('message.Expense delete successfully.'), $expense);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    function approve($request, $expense)
    {
        try {

            $expense->amount     = $request->amount;
            $expense->status_id  = $request->status;
            $expense->approver_id = auth()->user()->id;
            $expense->updated_by = auth()->user()->id;
            $expense->save();
            return $this->responseWithSuccess(_trans('message.Expense approved successfully.'), $expense);
        } catch (\Throwable $th) {
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    function pay($request, $expense)
    {
        DB::beginTransaction();
        try {

            $transaction                                 = new Transaction;
            $transaction->account_id                     = $request->account;
            $transaction->company_id                     = auth()->user()->company->id;
            $transaction->date                           = date('Y-m-d');
            $transaction->description                    = @$request->description ?? 'Expense Payment';
            $transaction->amount                         = $expense->amount;
            $transaction->transaction_type               = 18;
            $transaction->status_id                      = 8;
            $transaction->created_by                     = auth()->id();
            $transaction->updated_by                     = auth()->id();
            $transaction->save();

            $expense->transaction_id                     = $transaction->id;
            $expense->payment_method_id                  = $request->payment_method;
            $expense->pay                                = 8;
            $expense->save();

            $account = Account::findOrFail($request->account);
            $account->amount = $account->amount - $expense->amount;
            $account->save();

            DB::commit();
            return $this->responseWithSuccess(_trans('message.Paid successfully.'), $expense);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    // new Functions
    function table($request)
    {
        $params = [];
        $data = $this->model->query()->with('user:id,name', 'category:id,name', 'payment:id,name,class', 'status:id,name,class')->where('company_id', auth()->user()->company_id)
            ->select('company_id', 'date', 'pay', 'status_id', 'income_expense_category_id', 'id', 'user_id', 'created_at', 'amount', 'request_amount');
        if ($request->payment) {
            $params['pay'] = $request->payment;
        }
        if ($request->status) {
            $params['status_id'] = $request->status;
        }
        if (@$request->from && @$request->to) {
            $data = $data->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if ($request->search) {
            $data->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }
        $data = $data->where($params)->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('expense_view')) {
                    $action_button .= '<a href="' . route('hrm.expenses.show', $data->id) . '" class="dropdown-item">  <span class="icon mr-8"><i class="fa-solid fa-eye"></i></span>' . _trans('common.View') . '</a>';
                }

                if (hasPermission('expense_edit') && @$data->pay == 9) {
                    $action_button .= '<a href="' . route('hrm.expenses.edit', $data->id) . '" class="dropdown-item">  <span class="icon mr-8"><i class="fa-solid fa-pen-to-square"></i></span>' . _trans('common.Edit') . '</a>';
                }
                if (hasPermission('expense_approve') && @$data->pay == 9) {
                    $action_button .= actionButton(_trans('common.Approve'), 'mainModalOpen(`' . route('hrm.expenses.approve_modal', $data->id) . '`)', 'modal');
                }

                if (hasPermission('expense_pay') && $data->status_id == 5 && @$data->pay == 9) {
                    $action_button .= actionButton(_trans('common.Pay'), 'mainModalOpen(`' . route('hrm.expenses.pay', $data->id) . '`)', 'modal');
                }

                if (hasPermission('expense_delete') && $data->status_id != 5 && @$data->pay == 9) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/expenses/delete/`)', 'delete');
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
                    'id' => $data->id,
                    'employee' => @$data->user->name,
                    'category' => @$data->category->name,
                    'amount' => '<small class="text-info">' . _trans('payroll.Requested') . ' : ' . currency_format($data->request_amount) . '</small><br> 
                                    <small class="text-success">' . _trans('payroll.Approved') . ' : ' . currency_format($data->amount ?? 0) . '</small> <br>',
                    'payment' => '<small class="badge badge-' . @$data->payment->class . '">' . @$data->payment->name . '</small>',
                    'date' => showDate(@$data->created_at),
                    'status' => '<small class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</small>',
                    'action'   => $button
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
        DB::beginTransaction();
        try {
            if (@$request->ids) {
                $expenses = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->get();
                foreach ($expenses as $expense) {
                    if ($expense->attachment) {
                        $this->deleteImage(asset_path($expense->attachment));
                    }
                    $expense->attachmentFile->delete();
                    $expense->delete();
                }
                DB::commit();
                return $this->responseWithSuccess(_trans('message.Expense delete successfully.'), $expenses);
            } else {
                return $this->responseWithError(_trans('message.Expense not found'), [], 400);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function getMonthlyExpense()
    {
        try {
            $income     = [];
            $expense    = [];
            $date_array = [];
            $thisMonthArray = getMonthDays(\request()->get('month'));
            foreach ($thisMonthArray as $key => $item) {
                $day = Carbon::parse($item);
                $date_array[$key] = $day->format('d');
                $income[] = DB::table('deposits')->where('company_id', auth()->user()->company_id)->where('pay', 8)->where('status_id', 5)->whereDate('date', $item)->sum('amount');
                $expense[] = DB::table('expenses')->where('company_id', auth()->user()->company_id)->where('pay', 8)->where('status_id', 5)->whereDate('date', $item)->sum('amount');
            }
            $data['categories'] = [
                [
                    'name' => _trans('account.Deposit'),
                    'type' => 'bar',
                    'data' => $income,
                ],
                [
                    'name' => _trans('account.Expense'),
                    'type' => 'bar',
                    'data' => $expense,
                ],
            ];
            $data['date_array'] = $date_array;
            return  $this->responseWithSuccess('Expense list', $data, 200);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
