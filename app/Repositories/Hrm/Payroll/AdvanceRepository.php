<?php

namespace  App\Repositories\Hrm\Payroll;

use Validator;
use App\Models\Finance\Account;
use App\Models\Finance\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Finance\Transaction;
use Illuminate\Support\Facades\Log;
use App\Models\Payroll\AdvanceSalary;
use App\Models\Payroll\AdvanceSalaryLog;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class AdvanceRepository
{

    use ApiReturnFormatTrait, FileHandler;
    protected $model;

    public function __construct(AdvanceSalary $model)
    {
        $this->model = $model;
    }

    public function model($filter = null)
    {
        $model = $this->model;
        if ($filter) {
            $model = $this->model->where($filter)->first();
        }
        return $model;
    }
    
    function userDataTable($request, $user_id)
    {
        $content = $this->model->query()->with('employee:id,department_id,name')->where('company_id', auth()->user()->company_id);
        $params = [];
        if (@$request->status_id) {
            $params['status_id'] = $request->status_id;
        }
        $params['user_id'] = $user_id;

        if (@$request->pay) {
            $params['pay'] = $request->pay;
        }
        if (@$request->return_status) {
            $params['return_status'] = $request->return_status;
        }
        if (@$request->date) {
            $content = $content->whereMonth('date', date('m', strtotime($request->date)));
        }
        if (@$request->department_id) {
            $content->whereHas('employee', function ($query) use ($request) {
                $query->where('department_id', $request->department_id);
            });
        }
        $content = $content->where($params);
        $content = $content->latest()->get();
        return $this->generateDatatable($content);
    }

    public function dataTable($request)
    {
        $content = $this->model->query()->with('employee:id,department_id,name')->where('company_id', auth()->user()->company_id);
        $params = [];
        if (auth()->user()->role->slug == 'staff') {
            $params['user_id'] = auth()->user()->id;
        }
        if (@$request->status_id) {
            $params['status_id'] = $request->status_id;
        }
        if (@$request->user_id) {
            $params['user_id'] = $request->user_id;
        }
        if (@$request->pay) {
            $params['pay'] = $request->pay;
        }
        if (@$request->return_status) {
            $params['return_status'] = $request->return_status;
        }
        if (@$request->date) {
            $content = $content->whereMonth('date', date('m', strtotime($request->date)));
        }
        if (@$request->department_id) {
            $content->whereHas('employee', function ($query) use ($request) {
                $query->where('department_id', $request->department_id);
            });
        }
        $content = $content->where($params);
        $content = $content->latest()->get();
        return $this->generateDatatable($content);
    }
    function generateDatatable($content)
    {
        return datatables()->of($content)
            ->addColumn('action', function ($data) {
                $action_button = '';


                if (hasPermission('advance_salaries_view')) {
                    $action_button .= '<a href="' . route('hrm.payroll_advance_salary.show', $data->id) . '" class="dropdown-item"> ' . _trans('common.View') . '</a>';
                }
                if (hasPermission('advance_salaries_edit') && @$data->pay == 9) {
                    $action_button .= '<a href="' . route('hrm.payroll_advance_salary.edit', $data->id) . '" class="dropdown-item"> ' . _trans('common.Edit') . '</a>';
                }

                if (hasPermission('advance_salaries_approve') && @$data->pay == 9) {
                    if ($data->status_id == 5) {
                        $title = _trans('common.Already Approved');
                    } else {
                        $title = _trans('common.Approve');
                    }
                    $action_button .= actionButton($title, 'mainModalOpen(`' . route('hrm.payroll_advance_salary.approve_modal', $data->id) . '`)', 'modal');
                }
                if (hasPermission('advance_salaries_pay') && $data->status_id == 5 && @$data->pay == 9) {
                    $action_button .= actionButton(_trans('common.Pay'), 'mainModalOpen(`' . route('hrm.payroll_advance_salary.pay', $data->id) . '`)', 'modal');
                }
            

                if (hasPermission('advance_salaries_delete') && $data->status_id != 5 && @$data->pay == 9) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/payroll/advance-salary/delete/`)', 'delete');
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
                return $data->employee->name;
            })
            ->addColumn('amount', function ($data) {
                $class = 'info';
                if ($data->due_amount > 0) {
                    $class = 'danger';
                }
                $amount = '';
                $amount .= '<small class="text-info">' . _trans('payroll.Requested') . ' : ' . currency_format($data->request_amount) . '</small><br>';
                $amount .= '<small class="text-success">' . _trans('payroll.Approved') . ' : ' . currency_format($data->amount ?? 0) . '</small> <br>';
                $amount .= '<small class="text-' . $class . '">' . _trans('payroll.Returned') . ' : ' . currency_format($data->paid_amount ?? 0) . '</small>';
                return $amount;
            })
            ->addColumn('month', function ($data) {
                return '<small class="text-dark">' . date('F Y', strtotime($data->date)) . '</small>';
            })
            ->addColumn('advance_type', function ($data) {
                return '<small class="text-dark">' . $data->advance_type->name . '</small>';
            })
            ->addColumn('deduction', function ($data) {
                return '<small class="badge badge-' . @$data->payment->class . '">' . @$data->payment->name . '</small>';
            })
            ->addColumn('return_status', function ($data) {
                return '<small class="badge badge-' . @$data->returnPayment->class . '">' . @$data->returnPayment->name . '</small>';
            })
            ->addColumn('installment', function ($data) {
                return '<small class="text-success">' . currency_format($data->installment_amount) . '</small>';
            })
            ->addColumn('status', function ($data) {
                return '<small class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</small>';
            })
            ->addColumn('created_at', function ($data) {
                return date('d-m-Y', strtotime($data->created_at));
            })
            ->rawColumns(array('employee', 'amount', 'month', 'deduction', 'installment', 'status', 'advance_type', 'created_at', 'return_status', 'action'))
            ->make(true);
    }

    function store($request)
    {
        try {
            $advance = new $this->model;
            $advance->user_id = $request->user_id;
            $advance->date = $request->month;
            $advance->advance_type_id = $request->advance_type;
            $advance->request_amount = $request->amount;
            $advance->recovery_mode = $request->recovery_mode;
            $advance->recovery_cycle = $request->recovery_cycle;
            $advance->installment_amount = $request->installment_amount;
            $advance->recover_from = $request->recover_from;
            $advance->remarks = $request->reason;
            $advance->due_amount = $request->amount;
            $advance->company_id = auth()->user()->company_id;
            $advance->created_by = auth()->id();
            $advance->updated_by = auth()->id();
            $advance->save();
            return $this->responseWithSuccess(_trans('message.Advance pay created successfully.'), $advance);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function update($request, $id)
    {
        try {
            $advance = $this->model->findOrFail($id);
            $advance->user_id = $request->user_id;
            $advance->date = $request->month;
            $advance->advance_type_id = $request->advance_type;
            $advance->request_amount = $request->amount;
            $advance->recovery_mode = $request->recovery_mode;
            $advance->recovery_cycle = $request->recovery_cycle;
            $advance->installment_amount = $request->installment_amount;
            $advance->recover_from = $request->recover_from;
            $advance->remarks = $request->reason;
            $advance->status_id                      = 2;
            $advance->due_amount = $request->amount;
            $advance->updated_by = auth()->user()->id;
            $advance->save();
            return $this->responseWithSuccess(_trans('message.Advance pay update successfully.'), $advance);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function delete($params)
    {

        $advance = $this->model($params);
        if (!$advance) {
            return $this->responseWithError(_trans('Data not found'), 'id', 404);
        }
        try {
            $advance->delete();
            return $this->responseWithSuccess(_trans('message.Advance pay delete successfully.'), $advance);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function approve($params, $request)
    {
        $advance = $this->model($params);
        if (!$advance) {
            return $this->responseWithError(_trans('Data not found'), 'id', 404);
        }

        try {

            $advance->amount     = $request->amount;
            $advance->status_id  = $request->status;
            $advance->due_amount = $request->amount;
            $advance->approver_id = auth()->user()->id;
            $advance->updated_by = auth()->user()->id;
            $advance->save();
            return $this->responseWithSuccess(_trans('message.Advance pay approved successfully.'), $advance);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    function pay($request, $params)
    {
        DB::beginTransaction();
        try {
            $advance = $this->model($params);
            if (!$advance) {
                return $this->responseWithError(_trans('Data not found'), 'id', 404);
            }
            $advance->pay = 8;
            $advance->save();


            $transaction                                 = new Transaction;
            $transaction->account_id                     = $request->account;
            $transaction->company_id                     = auth()->user()->company->id;
            $transaction->date                           = date('Y-m-d');
            $transaction->description                    = @$request->description ?? 'advance Payment';
            $transaction->amount                         = $advance->amount;
            $transaction->transaction_type               = 18;
            $transaction->status_id                      = 8;
            $transaction->created_by                     = auth()->id();
            $transaction->updated_by                     = auth()->id();
            $transaction->save();

            $expense                                 = new Expense();
            $expense->user_id                        = $advance->user_id;
            $expense->income_expense_category_id     = $request->category;
            $expense->company_id                     = auth()->user()->company->id;
            $expense->date                           = date('Y-m-d');
            $expense->amount                         = $advance->amount;
            $expense->request_amount                 = $advance->amount;
            $expense->ref                            = auth()->user()->name;
            $expense->remarks                        = $request->description ?? 'advance Payment';
            $expense->created_by                     = auth()->id();
            $expense->updated_by                     = auth()->id();
            $expense->approver_id                    = auth()->user()->id;
            $expense->status_id                      = 5;
            if ($request->hasFile('attachment')) {
                $expense->attachment                 = $this->uploadImage($request->attachment, 'expense')->id;
            }
            $expense->transaction_id                 = $transaction->id;
            $expense->payment_method_id              = $request->payment_method;
            $expense->pay                            = 8;
            $expense->save();


            $advanceSalaryLog                        = new AdvanceSalaryLog();
            $advanceSalaryLog->advance_salary_id     = $advance->id;
            $advanceSalaryLog->is_pay                = 0;
            $advanceSalaryLog->amount                = $advance->amount;
            $advanceSalaryLog->due_amount            = $advance->amount;
            $advanceSalaryLog->user_id               = $advance->user_id;
            $expense->transaction_id                 = $transaction->id;
            $expense->payment_method_id              = $request->payment_method;
            $expense->description                    = $request->description;
            $advanceSalaryLog->created_by            = auth()->id();
            $advanceSalaryLog->updated_by            = auth()->id();
            $advanceSalaryLog->save();


            $account = Account::findOrFail($request->account);
            $account->amount = $account->amount - $transaction->amount;
            $account->save();

            DB::commit();
            return $this->responseWithSuccess(_trans('message.Paid successfully.'), $advance);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    // new functions

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Employee'),
            _trans('common.Advance Type'),
            _trans('common.Amount'),
            _trans('common.Month'),
            _trans('common.Payment'),
            _trans('common.Return'),
            _trans('common.Installment'),
            // _trans('common.Created At'),
            _trans('common.Status'),
            _trans('common.Action')
        ];
    }

    function table($request)
    {
        
        $data = $this->model->query()->with('employee:id,department_id,name')->where('company_id', auth()->user()->company_id);
        $params = [];
        if (auth()->user()->role->slug == 'staff') {
            $params['user_id'] = auth()->user()->id;
        }
        if (@$request->status) {
            $params['status_id'] = $request->status;
        }
        if (@$request->user_id) {
            $params['user_id'] = $request->user_id;
        }
        if (@$request->payment) {
            $params['pay'] = $request->payment;
        }
        if (@$request->return_status) {
            $params['return_status'] = $request->return_status;
        }
        if ($request->from && $request->to) {
            $data = $data->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if (@$request->department) {
            $data->whereHas('employee', function ($query) use ($request) {
                $query->where('department_id', $request->department);
            });
        }
        if ($request->search) {
            $data->whereHas('employee', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }
        $data = $data->where($params)->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('advance_salaries_view')) {
                    $action_button .= '<a href="' . route('hrm.payroll_advance_salary.show', $data->id) . '" class="dropdown-item">  <span class="icon mr-8"><i class="fa-regular fa-eye"></i></span>' . _trans('common.View') . '</a>';
                }
                if (hasPermission('advance_salaries_edit') && @$data->pay == 9) {
                    $action_button .= '<a href="' . route('hrm.payroll_advance_salary.edit', $data->id) . '" class="dropdown-item">  <span class="icon mr-8"><i class="fa-solid fa-pen-to-square"></i></span>' . _trans('common.Edit') . '</a>';
                }

                if (hasPermission('advance_salaries_approve') && @$data->pay == 9) {
                    if ($data->status_id == 5) {
                        $title = _trans('common.Already Approved');
                    } else {
                        $title = _trans('common.Approve');
                    }
                    $action_button .= actionButton($title, 'mainModalOpen(`' . route('hrm.payroll_advance_salary.approve_modal', $data->id) . '`)', 'modal');
                }
                if (hasPermission('advance_salaries_pay') && $data->status_id == 5 && @$data->pay == 9) {
                    $action_button .= actionButton(_trans('common.Pay'), 'mainModalOpen(`' . route('hrm.payroll_advance_salary.pay', $data->id) . '`)', 'modal');
                }

                if (hasPermission('advance_salaries_delete') && $data->status_id != 5 && @$data->pay == 9) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/payroll/advance-salary/delete/`)', 'delete');
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

                $amount = $class = 'info';
                if ($data->due_amount > 0) {
                    $class = 'danger';
                }
                $amount = '';
                $amount .= '<small class="text-info">' . _trans('payroll.Requested') . ' : ' . currency_format($data->request_amount) . '</small><br>';
                $amount .= '<small class="text-success">' . _trans('payroll.Approved') . ' : ' . currency_format($data->amount ?? 0) . '</small> <br>';
                $amount .= '<small class="text-' . $class . '">' . _trans('payroll.Returned') . ' : ' . currency_format($data->paid_amount ?? 0) . '</small>';

                return [
                    'id'             => $data->id,
                    'employee'       => $data->employee->name,
                    'amount'         => $amount,
                    'month'          =>  date('F Y', strtotime($data->date)),
                    'advance_type'   => $data->advance_type->name,
                    'payment'        => '<small class="badge badge-' . @$data->payment->class . '">' . @$data->payment->name . '</small>',
                    'return_status'  => '<small class="badge badge-' . @$data->returnPayment->class . '">' . @$data->returnPayment->name . '</small>',
                    'installment'    => '<small class="text-success">' . currency_format($data->installment_amount) . '</small>',
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
}
