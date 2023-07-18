<?php

namespace  App\Repositories\Hrm\Payroll;

use App\Models\User;
use App\Models\Finance\Account;
use App\Models\Finance\Expense;
use Illuminate\Support\Facades\DB;
use App\Models\Finance\Transaction;
use Illuminate\Support\Facades\Log;
use App\Models\Payroll\AdvanceSalary;
use App\Models\Payroll\SalaryGenerate;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Payroll\AdvanceSalaryLog;
use App\Models\Payroll\SalaryPaymentLog;
use Illuminate\Support\Facades\Validator;
use App\Models\Payroll\SalarySetupDetails;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class SalaryRepository
{
    use ApiReturnFormatTrait;
    protected $model;

    public function __construct(SalaryGenerate $model)
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

    public function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Name'),
            _trans('payroll.Salary Type'),
            _trans('payroll.Calculation'),
            _trans('payroll.Salary'),
            _trans('payroll.Month'),
            _trans('payroll.Status'),
            _trans('payroll.Action'),
        ];
    }

    public function dataTable($request)
    {

        $content = $this->model->query()->with('employee:id,name,department_id,payslip_type')->where('company_id', auth()->user()->company_id);
        $params = [];
        if (auth()->user()->role->slug == 'staff') {
            $params['user_id'] = auth()->user()->id;
        }
        if (@$request->status_id) {
            $params['status_id'] = $request->status_id;
        }
        if (@$request->date) {
            $content = $content->whereMonth('date', date('m', strtotime($request->date)));
        }
        if (@$request->department_id) {
            $content->whereHas('employee', function ($query) use ($request) {
                $query->where('department_id', $request->department_id);
            });
        }
        $content = $content->where($params)->latest()->get();
        return $this->generateDatatable($content);
    }
    public function staffDataTable($request)
    {

        $content = $this->model->query()->with('employee:id,name,department_id,payslip_type')->where('company_id', auth()->user()->company_id);
        $params = [];
        $params['user_id'] = auth()->user()->id;
        if (@$request->status_id) {
            $params['status_id'] = $request->status_id;
        }
        if (@$request->date) {
            $content = $content->whereMonth('date', date('m', strtotime($request->date)));
        }
        if (@$request->department_id) {
            $content->whereHas('employee', function ($query) use ($request) {
                $query->where('department_id', $request->department_id);
            });
        }
        $content = $content->where($params)->latest()->get();
        return $this->generateDatatable($content);
    }
    public function userDataTable($request, $user_id)
    {

        $content = $this->model->query()->with('employee:id,name,department_id,payslip_type')->where('company_id', auth()->user()->company_id);
        $params = [];
        $params['user_id'] = $user_id;
        if (@$request->status_id) {
            $params['status_id'] = $request->status_id;
        }
        if (@$request->date) {
            $content = $content->whereMonth('date', date('m', strtotime($request->date)));
        }
        if (@$request->department_id) {
            $content->whereHas('employee', function ($query) use ($request) {
                $query->where('department_id', $request->department_id);
            });
        }
        $content = $content->where($params)->latest()->get();
        return $this->generateDatatable($content);
    }

    function generateDatatable($content)
    {
        return datatables()->of($content)
            ->addColumn('action', function ($data) {
                $action_button = '';


                if (hasPermission('salary_view')) {
                    $action_button .= '<a href="' . route('hrm.payroll_salary.show', $data->id) . '" class="dropdown-item"> ' . _trans('common.View') . '</a>';
                }
                if (hasPermission('salary_calculate') && $data->is_calculated == 0) {
                    $action_button .= actionButton(_trans('common.Calculate'), 'mainModalOpen(`' . route('hrm.payroll_salary.calculate_modal', $data->id) . '`)', 'modal');
                }
                if (hasPermission('salary_pay') && $data->status_id != 8 && $data->is_calculated == 1) {
                    $action_button .= actionButton(_trans('common.Pay'), 'mainModalOpen(`' . route('hrm.payroll_salary.pay', $data->id) . '`)', 'modal');
                }
                if (hasPermission('salary_invoice')) {
                    $action_button .= '<a href="' . route('hrm.payroll_salary.invoice', $data->id) . '" class="dropdown-item"> ' . _trans('common.Payslip') . '</a>';
                }

                if (hasPermission('salary_delete') && $data->status_id == 9) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/payroll/salary/delete/`)', 'delete');
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
                $id = $data->employee->employee_id ?? '0000';
                if (hasPermission('salary_view')) {
                    return '<a class="text-success text-decoration-none text-muted" href="' . route('hrm.payroll_salary.show', $data->id) . '" class="dropdown-item"> #' . $id . '</a>';
                } else {
                    return '<a" class="text-success' . '"> #' . $id . '</a>';
                }
            })
            ->addColumn('name', function ($data) {
                return $data->employee->name;
            })
            ->addColumn('salary', function ($data) {
                $amount = '';
                $amount .= '<small class="text-info">' . currency_format($data->gross_salary) . '</small><br>';
                return $amount;
            })
            ->addColumn('month', function ($data) {
                return '<small class="text-dark">' . date('F Y', strtotime($data->date)) . '</small>';
            })
            ->addColumn('type', function ($data) {
                return $data->employee->payslip_type ? 'Monthly' : 'Yearly';
            })
            ->addColumn('is_calculated', function ($data) {
                if (!$data->is_calculated) {
                    return '<small class="badge badge-danger">' . _trans('common.No') . '</small>';
                }
                $content = '';
                $content .= '<small class="text-success">' . _trans('payroll.Addition') . ' : ' . currency_format(number_format($data->allowance_amount, 2)) . '</small><br>';
                $content .= '<small class="text-danger">' . _trans('payroll.Deduction') . ' : ' . currency_format(number_format(($data->deduction_amount + $data->absent_amount + $data->advance_amount), 2)) . '</small><br>';
                $content .= '<small class="text-success">' . _trans('payroll.Adjust Salary') . ' : ' . currency_format(number_format($data->adjust, 2)) . '</small><br>';
                $content .= '<small class="text-info">' . _trans('payroll.Net Salary') . ' : ' . currency_format(number_format($data->net_salary, 2)) . '</small><br>';
                return $content;
            })
            ->addColumn('status', function ($data) {
                return '<small class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</small>';
            })
            ->rawColumns(array('employee', 'name', 'month', 'salary', 'type', 'status', 'is_calculated',  'action'))
            ->make(true);
    }

    public function weekends($date)
    {
        $workdays = array();
        $type = CAL_GREGORIAN;
        $month =  date('n', strtotime($date));
        $weekends = array();
        $year = date('Y', strtotime($date));
        $day_count = cal_days_in_month($type, $month, $year);
        if (!Session::has('weekends')) {
            Session::put('weekends', DB::table('weekends')->where('company_id', auth()->user()->company_id)->where('is_weekend', 'yes')->pluck('name')->toArray());
        }
        //loop through all days
        for ($i = 1; $i <= $day_count; $i++) {
            $date = $year . '/' . $month . '/' . $i;
            if (!in_array(strtolower(date('l', strtotime($date))), Session::get('weekends'))) {
                $workdays[] = date('Y-m-d', strtotime($date));
            } else {
                $weekends[] = date('Y-m-d', strtotime($date));
            }
        }
        return  [
            'workdays' => $workdays,
            'weekends' => $weekends,
        ];
    }

    public function join_weekends($join_date)
    {
        $workdays = array();
        $type = CAL_GREGORIAN;
        $month =  date('n', strtotime($join_date));
        $weekends = array();
        $year = date('Y', strtotime($join_date));
        $day_count = cal_days_in_month($type, $month, $year);
        if (!Session::has('weekends')) {
            Session::put('weekends', DB::table('weekends')->where('company_id', auth()->user()->company_id)->where('is_weekend', 'yes')->pluck('name')->toArray());
        }
        //loop through all days
        for ($i = 1; $i <= $day_count; $i++) {
            $date = $year . '/' . $month . '/' . $i;
            if (!in_array(strtolower(date('l', strtotime($date))), Session::get('weekends')) && strtotime($date) >= strtotime($join_date)) {
                $workdays[] = date('Y-m-d', strtotime($date));
            } else {
                $weekends[] = date('Y-m-d', strtotime($date));
            }
        }
        return  [
            'workdays' => $workdays,
            'weekends' => $weekends,
        ];
    }

    

    public  function holiday($date, $weekends)
    {
        $workdays = array();
        foreach (DB::table('holidays')->where('company_id', auth()->user()->company_id)->whereMonth('start_date', date('m', strtotime($date)))->orWhereMonth('end_date', date('m', strtotime($date)))->get() as $holiday) {
            $current_date = strtotime($holiday->start_date);
            $end_date = strtotime($holiday->end_date);
            while ($current_date <= $end_date) {
                if (date('N', $current_date)  && !in_array(date('Y-m-d', $current_date), $weekends) && date('m', strtotime($date)) == date('m', $current_date)) {
                    $workdays[] = date('Y-m-d', $current_date);
                }
                if ($current_date <= $end_date) {
                    $current_date = strtotime('+1 day', $current_date);
                }
            }
        }
        return $workdays;
    }

    public function getDay($start_date, $end_date, $date)
    {
        $start_date = strtotime($start_date);
        $end_date = strtotime($end_date);
        $workdays = array();
        while ($start_date <= $end_date) {
            if (date('N', $start_date) && date('m', strtotime($date)) == date('m', $start_date)) {
                $workdays[] = date('Y-m-d', $start_date);
            }
            if ($start_date <= $end_date) {
                $start_date = strtotime('+1 day', $start_date);
            }
        }
        return $workdays;
    }

    public function getLeave($date, $user, $per_day_salary, $total_absent)
    {
        if ($total_absent <= 0) {
            return 0;
        }
        $leave         = DB::table('leave_requests')->where('company_id', auth()->user()->company_id)->where('user_id', $user->id)->where('status_id', 1);
        $yearlyLeave   =  0;
        $monthlyLeave  = $user->extra_leave ?? 0;
        foreach ($leave->clone()->whereYear('leave_from', '<=', date('Y', strtotime($date)))->whereYear('leave_to', '>=', date('Y', strtotime($date)))->get() as $yLeave) {
            $yearlyLeave += intval($this->getDay($yLeave->leave_from, $yLeave->leave_to, $date));
        }
        foreach ($leave->clone()->whereMonth('leave_from', '<=', date('m', strtotime($date)))->whereMonth('leave_to', '>=', date('m', strtotime($date)))->get() as $mLeave) {
            $monthlyLeave += intval($this->getDay($mLeave->leave_from, $mLeave->leave_to, $date));
        }

        $minus_salary  = 0;

        $total_leaves  = ($user->Leave->count() + $user->extra_leave) - $yearlyLeave;

        if ($total_leaves > 0) {
            $minus_salary  =  ($monthlyLeave - $total_absent)  * $per_day_salary;
        } else {
            $minus_salary  = $total_absent * $per_day_salary;
        }
        return abs($minus_salary);
    }


    public function generate($request)
    {
        $validator = Validator::make(\request()->all(), [
            'month' => 'required',
            'department' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Required field missing'), $validator->errors(), 400);
        }
        DB::beginTransaction();
        try {
            $where  = [
                'company_id' => auth()->user()->company_id
            ];
            if (@$request->department) {
                $where[] = ['department_id', $request->department];
            }
            $users    = DB::table('users')->where($where)->pluck('id');
            $salary = $this->model->where('company_id', auth()->user()->company_id)->whereMonth('date', date('m', strtotime($request->month)))->whereIn('user_id', $users)->get();
            if (!blank($salary)) {
                return $this->responseWithError(_trans('message.Salary already generated'), [], 400);
            }

            foreach ($users as $key => $value) {
                $user = User::with('Leave')->where('id', $value)->first();
                if (!blank($user)) {
                    $salary                 = new SalaryGenerate();
                    $salary->user_id = $user->id;
                    $salary->company_id = auth()->user()->company_id;
                    $salary->date = date('Y-m-d', strtotime($request->month));
                    $salary->amount = $user->basic_salary;
                    $salary->gross_salary = $user->basic_salary;
                    $salary->created_by = auth()->user()->id;
                    $salary->updated_by = auth()->user()->id;
                    $salary->net_salary = $user->basic_salary;
                    $salary->save();
                }
            }
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Salary generated successfully.'), $salary);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    public function info($params)
    {
        try {
            $salary_info = $this->model($params)->first();
            $date = $salary_info->date;
            $user = $salary_info->employee;
            if (strtotime($user->joining_date) > strtotime($date)) {
                $weekends = $this->join_weekends($user->joining_date);
                $holiday               = $this->holiday($date, $weekends['weekends']);
            }else {
                //total weekends
                $weekends              = $this->weekends($date);
                //total holidays
                $holiday               = $this->holiday($date, $weekends['weekends']);
            }
            // total working days
            $total_working_days    = count($weekends['workdays']) - (count($holiday));


            $raw               =  DB::table('attendances')->where('company_id', auth()->user()->company_id)->where('user_id', $user->id)->whereMonth('date', date('m', strtotime($date)));
            $checkinAtt        = $raw->clone()->orderby('id', 'asc')->groupBy('date')->get();
            // $checkoutAtt       = $raw->clone()->orderby('id', 'desc')->get()->unique('date');

            $total_present = $checkinAtt->count();

            $total_absent  = $total_working_days - $total_present;
            $total_late    = $raw->clone()->where('in_status', 'L')->orderby('id', 'asc')->groupBy('date')->count();
            $total_early   = $raw->clone()->where('out_status', 'LE')->orderby('id', 'desc')->get()->unique('date')->count();
            $per_day_salary = ($user->basic_salary / $total_working_days);
            $leave_cuts = $this->getLeave($date, $user, $per_day_salary, $total_absent);

            //advance salary
            $advance_salary = AdvanceSalary::with('payment', 'advance_type')
                ->where('status_id', 5)
                ->where('company_id', auth()->user()->company_id)
                ->where('user_id', $user->id)
                ->whereMonth('recover_from', date('m', strtotime($date)));

            //installment salary
            $installment = $advance_salary->clone()->where('recovery_mode', 1)->sum('installment_amount');

            //onetime salary
            $onetime = $advance_salary->clone()->where('recovery_mode', 2)->sum('amount');

            // commission salary
            $commission = SalarySetupDetails::with('commission:id,type,name')
                ->where('status_id', 1)
                ->where('company_id', auth()->user()->company_id)
                ->where('user_id', $user->id)->get();
            $addition = 0;
            $deduction = 0;
            //json addition and deduction details
            $addition_detail = [];
            $deduction_detail = [];
            foreach ($commission as $key => $value) {
                if ($value->commission->type == 1) {
                    $addition += $value->amount_type == 1 ? $value->amount : (($value->amount / 100) * $user->basic_salary);
                    $addition_detail[] = [
                        'type' => @$value->commission->type,
                        'amount_type' => @$value->amount_type,
                        'amount' => @$value->amount_type == 1 ? $value->amount : (($value->amount / 100) * $user->basic_salary),
                        'old_amount' => @$value->amount,
                        'name' => @$value->commission->name,
                    ];
                } else {
                    $deduction += $value->amount_type == 1 ? $value->amount : (($value->amount / 100) * $user->basic_salary);
                    $deduction_detail[] = [
                        'type' => @$value->commission->type,
                        'amount_type' => @$value->amount_type,
                        'old_amount' => @$value->amount,
                        'amount' => @$value->amount_type == 1 ? $value->amount : (($value->amount / 100) * $user->basic_salary),
                        'name' => @$value->commission->name,
                    ];
                }
            }

            return [
                'total_working_days' => $total_working_days,
                'total_present' => $total_present,
                'total_absent' => $total_absent,
                'total_late' => $total_late,
                'total_early' => $total_early,
                'per_day_salary' => $per_day_salary,
                'advance_salary' => $advance_salary->get(),
                'leave_cuts' => $leave_cuts,
                'installment' => $installment,
                'onetime' => $onetime,
                'addition' => $addition,
                'deduction' => $deduction,
                'addition_detail' => $addition_detail,
                'deduction_detail' => $deduction_detail,
                'net_salary' => (($user->basic_salary + $addition) - ($deduction + $leave_cuts + $installment + $onetime)),
            ];
        } catch (\Throwable $th) {
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    public function calculate($request, $params)
    {
        DB::beginTransaction();
        try {

            $salary_info = $this->model($params)->first();
            if (@$salary_info->is_calculated) {
                return $this->responseWithError(_trans('message.Salary already calculated!'), 'id', 404);
            }
            $info = $this->info($params);
            $salary_info->amount = floatval($info['net_salary']) + floatval($request->adjust);
            $salary_info->due_amount = $salary_info->amount;
            $salary_info->total_working_day = $info['total_working_days'];
            $salary_info->present = $info['total_present'];
            $salary_info->absent = $info['total_absent'];
            $salary_info->late = $info['total_late'];
            $salary_info->left_early = $info['total_early'];
            $salary_info->allowance_amount = $info['addition'];
            $salary_info->allowance_details = ($info['addition_detail']);
            $salary_info->deduction_amount = $info['deduction'];
            $salary_info->deduction_details = ($info['deduction_detail']);
            $salary_info->absent_amount = $info['leave_cuts'];
            $salary_info->net_salary = $salary_info->amount;
            $salary_info->adjust = floatval($request->adjust);
            $salary_info->is_calculated = 1;
            $salary_info->advance_amount = $info['installment'] + $info['onetime'];
            $salary_info->advance_details =  $info['advance_salary'];
            $salary_info->save();

            if ($salary_info->advance_amount > 0) {
                foreach ($salary_info->advance_details as $key => $value) {
                    $advance                              = AdvanceSalary::find($value['id']);
                    $get_amount         = $advance->recovery_mode == 1 ? $advance->installment_amount : $advance->amount;
                    $advance->due_amount = $advance->due_amount - $get_amount;
                    $advance->paid_amount = $advance->paid_amount + $get_amount;
                    $advance->updated_by = auth()->id();
                    if ($advance->due_amount <= 0) {
                        $advance->pay = 8;
                        $advance->return_status = 23;
                    } else {
                        $advance->return_status = 21;
                    }
                    $advance->save();
                    if (@$advance) {
                        $advanceSalaryLog                        = new AdvanceSalaryLog();
                        $advanceSalaryLog->advance_salary_id     = $advance->id;
                        $advanceSalaryLog->is_pay                = 1;
                        $advanceSalaryLog->amount                = $get_amount;
                        $advanceSalaryLog->due_amount            = $advance->due_amount;
                        $advanceSalaryLog->user_id               = $advance->user_id;
                        $advanceSalaryLog->payment_note           = @$request->description ?? 'Payment';
                        $advanceSalaryLog->created_by            = auth()->id();
                        $advanceSalaryLog->updated_by            = auth()->id();
                        $advanceSalaryLog->save();
                    }
                }
            }

            DB::commit();
            return $this->responseWithSuccess(_trans('message.Salary generated successfully.'), $salary_info);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    function pay($request, $salary)
    {

        DB::beginTransaction();
        try {
            if ($salary->status_id == 8) {
                return $this->responseWithError(_trans('message.Salary already Paid'));
            }

            $transaction                                 = new Transaction;
            $transaction->account_id                     = $request->account;
            $transaction->company_id                     = $salary->company_id;
            $transaction->date                           = date('Y-m-d');
            $transaction->description                    = @$request->description ?? 'salary Payment';
            $transaction->amount                         = $request->amount;
            $transaction->transaction_type               = 18;
            $transaction->status_id                      = 8;
            $transaction->created_by                     = auth()->id();
            $transaction->updated_by                     = auth()->id();
            $transaction->save();

            $expense                                 = new Expense();
            $expense->user_id                        = $salary->user_id;
            $expense->income_expense_category_id     = $request->category;
            $expense->company_id                     = $salary->company_id;
            $expense->date                           = date('Y-m-d');
            $expense->amount                         = $request->amount;
            $expense->request_amount                 = $request->amount;
            $expense->ref                            = auth()->user()->name;
            $expense->remarks                        = $request->description ?? 'salary Payment';
            $expense->created_by                     = auth()->id();
            $expense->updated_by                     = auth()->id();
            $expense->approver_id                    = auth()->user()->id;
            $expense->transaction_id                 = $transaction->id;
            $expense->payment_method_id              = $request->payment_method;
            $expense->pay                            = 8;
            $expense->status_id                      = 5;
            $expense->save();



            $salaryPaymentLog                        = new SalaryPaymentLog();
            $salaryPaymentLog->salary_generate_id    = $salary->id;
            $salaryPaymentLog->amount                = $request->amount;
            $salaryPaymentLog->user_id               = $salary->user_id;
            $salaryPaymentLog->due_amount            = $salary->amount - $request->amount;
            $salaryPaymentLog->paid_by               = $salary->user_id;
            $salaryPaymentLog->transaction_id        = $transaction->id;
            $salaryPaymentLog->payment_method_id     = $request->payment_method;
            $salaryPaymentLog->payment_note          = $request->description ?? 'salary Payment';
            $salaryPaymentLog->created_by            = auth()->id();
            $salaryPaymentLog->updated_by            = auth()->id();
            $salaryPaymentLog->company_id            = $salary->company_id;
            $salaryPaymentLog->save();


            $account = Account::findOrFail($request->account);
            $account->amount = $account->amount - $transaction->amount;
            $account->save();

            $salary->due_amount = $salary->due_amount - $request->amount;
            $salary->updated_by = auth()->id();
            if ($salary->due_amount <= 0) {
                $salary->status_id = 8;
            } else {
                $salary->status_id = 20;
            }
            $salary->save();


            DB::commit();
            return $this->responseWithSuccess(_trans('message.Salary pay successfully.'), $salary);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }


    function delete($id, $company_id)
    {
        $salary = $this->model()->where('id', $id)->where('company_id', $company_id)->first();
        if (!$salary) {
            return $this->responseWithError(_trans('message.Data not found'), 'id', 404);
        }

        try {
            if ($salary->status_id == 9) {
                $salary->delete();
                return $this->responseWithSuccess(_trans('message.Payslip Delete successfully.'), $salary);
            } else {
                return $this->responseWithError(_trans('message.You cannot delete'), 'id', 404);
            }
        } catch (\Throwable $th) {
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    // new functions for
    public function table($request)
    {

        $data = $this->model->query()->with('employee:id,name,department_id,payslip_type')->where('company_id', auth()->user()->company_id);
        $params = [];
        if (auth()->user()->role->slug == 'staff') {
            $params['user_id'] = auth()->user()->id;
        }
        if ($request->has('user_id')) {
            $params['user_id'] = $request->user_id;
        }
        if (@$request->status_id) {
            $params['status_id'] = $request->status_id;
        }
        if ($request->from && $request->to) {
            $data = $data->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if ($request->search) {
            $data->whereHas('employee', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }
        if (@$request->department) {
            $data->whereHas('employee', function ($query) use ($request) {
                $query->where('department_id', $request->department);
            });
        }
        $data = $data->where($params)->latest()->paginate($request->limit ?? 2);;
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('salary_view')) {
                    $action_button .= '<a href="' . route('hrm.payroll_salary.show', $data->id) . '" class="dropdown-item"> ' . _trans('common.View') . '</a>';
                }
                if (hasPermission('salary_calculate') && $data->is_calculated == 0) {
                    $action_button .= actionButton(_trans('common.Calculate'), 'mainModalOpen(`' . route('hrm.payroll_salary.calculate_modal', $data->id) . '`)', 'modal');
                }
                if (hasPermission('salary_pay') && $data->status_id != 8 && $data->is_calculated == 1) {
                    $action_button .= actionButton(_trans('common.Pay'), 'mainModalOpen(`' . route('hrm.payroll_salary.pay', $data->id) . '`)', 'modal');
                }
                if (hasPermission('salary_invoice')) {
                    $action_button .= '<a href="' . route('hrm.payroll_salary.invoice', $data->id) . '" class="dropdown-item"> ' . _trans('common.Payslip') . '</a>';
                }

                if (hasPermission('salary_delete') && $data->status_id == 9) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/payroll/salary/delete/`)', 'delete');
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
                $is_calculated = '';
                if ($data->is_calculated) {
                    $is_calculated .= '<small class="text-success">' . _trans('payroll.Addition') . ' : ' . currency_format(number_format($data->allowance_amount, 2)) . '</small><br>';
                    $is_calculated .= '<small class="text-danger">' . _trans('payroll.Deduction') . ' : ' . currency_format(number_format(($data->deduction_amount + $data->absent_amount + $data->advance_amount), 2)) . '</small><br>';
                    $is_calculated .= '<small class="text-success">' . _trans('payroll.Adjust Salary') . ' : ' . currency_format(number_format($data->adjust, 2)) . '</small><br>';
                    $is_calculated .= '<small class="text-info">' . _trans('payroll.Net Salary') . ' : ' . currency_format(number_format($data->net_salary, 2)) . '</small><br>';
                }else {
                    $is_calculated = '<small class="badge badge-danger">' . _trans('common.No') . '</small>';                    
                }

                return [
                    'id'             => $data->id,
                    'employee'       => $data->employee->name,
                    'salary'         => showAmount($data->gross_salary),
                    'month'          =>  date('F Y', strtotime($data->date)),
                    'type'           => $data->employee->payslip_type ? 'Monthly' : 'Yearly',
                    'is_calculated'  => $is_calculated,
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

    function getMonthlyPayroll(){
        $data = [];
        $category = [];
        $info = [];
        for ($i = 1; $i <= 12; $i++) {
            $category[] = date('F', mktime(0, 0, 0, $i, 10));
            $info[] =  $this->model->query()->where('company_id', auth()->user()->company_id)->whereMonth('date', $i)->sum('amount');
        }
        $data['categories'] =  [
            [
                'name' => _trans('payroll.Payroll'),
                'type' => 'bar',
                'data' => $info,
            ]
        ];
        $data['date_array'] = $category;
        return $data;

    }
}
