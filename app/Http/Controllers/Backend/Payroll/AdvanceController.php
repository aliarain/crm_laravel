<?php

namespace App\Http\Controllers\Backend\Payroll;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\Company\CompanyRepository;
use App\Http\Requests\Payroll\CreateAdvanceRequest;
use App\Http\Requests\Payroll\UpdateAdvanceRequest;
use App\Repositories\Hrm\Finance\AccountRepository;
use App\Repositories\Hrm\Payroll\AdvanceRepository;
use App\Repositories\Hrm\Payroll\AdvanceTypeRepository;
use App\Repositories\Hrm\Attendance\AttendanceRepository;
use App\Repositories\Hrm\Department\DepartmentRepository;
use App\Repositories\Hrm\Expense\ExpenseCategoryRepository;

class AdvanceController extends Controller
{

    protected $accountRepository;
    protected $advanceRepository;
    protected $attendanceRepository;
    protected $advanceTypeRepository;
    protected $expenseCategoryRepository;
    protected $companyRepository;
    protected $department;
    protected $user;

    public function __construct(
        AccountRepository $accountRepository,
        DepartmentRepository $department,
        AdvanceRepository $advanceRepository,
        AttendanceRepository $attendanceRepository,
        AdvanceTypeRepository $advanceTypeRepository,
        ExpenseCategoryRepository $incomeExpenseCategoryRepository,
        CompanyRepository $companyRepository,
        UserRepository $user
    ) {
        $this->accountRepository = $accountRepository;
        $this->advanceRepository = $advanceRepository;
        $this->attendanceRepository = $attendanceRepository;
        $this->advanceTypeRepository = $advanceTypeRepository;
        $this->companyRepository = $companyRepository;
        $this->incomeExpenseCategoryRepository = $incomeExpenseCategoryRepository;
        $this->department = $department;
        $this->user = $user;
    }

    function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $this->advanceRepository->table($request);
            }
            $data['class']      = 'salary_advance_table';
            $data['fields']     = $this->advanceRepository->fields();
            $data['table']      = route('hrm.payroll_advance_salary.index');
            $data['url_id']     = 'salary_advance_table_url';

            $data['title'] = 'Advance';
            $data['departments'] = $this->department->getAll();
            return view('backend.payroll.advance.index', compact('data'));
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    function datatable(Request $request)
    {
        try {
            return $this->advanceRepository->dataTable($request);
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    function create()
    {
        try {

            $data['title']         = 'Advance Create';
            $data['advance_types'] = $this->advanceTypeRepository->getTypes();
            $data['url']           = (hasPermission('advance_salaries_store')) ? route('hrm.payroll_advance_salary.store') : '';
            return view('backend.payroll.advance.create', compact('data'));
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    function store(CreateAdvanceRequest $request)
    {
        try {
            $result = $this->advanceRepository->store($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.payroll_advance_salary.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    function edit($id)
    {
        try {
            $data['title']         = 'Advance Edit';
            $params                = [
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ];
            if (auth()->user()->role->slug == 'staff') {
                $params['user_id'] = auth()->user()->id;
            }
            $data['advance']       = $this->advanceRepository->model($params);
            $data['advance_types'] = $this->advanceTypeRepository->model()->get();
            $data['url']           = (hasPermission('advance_salaries_update')) ? route('hrm.payroll_advance_salary.update', [$id, $this->companyRepository->company()->id]) : '';
            if (auth()->user()->role->slug == 'staff' && $data['advance']->status_id != 2) {
                $data['url'] = '';
            }
            return view('backend.payroll.advance.create', compact('data'));
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    function update(UpdateAdvanceRequest $request, $id, $company)
    {
        try {
            $result = $this->advanceRepository->update($request, $id, $company);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.payroll_advance_salary.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    function delete($id)
    {
        try {
            $params                = [
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ];
            if (auth()->user()->role->slug == 'staff') {
                $params['user_id'] = auth()->user()->id;
            }
            $advance       = $this->advanceRepository->model($params);
            if (!$advance) {
                Toastr::error('Advance not found!', 'Error');
                return redirect()->route('hrm.payroll_advance_salary.index');
            }
            $result = $this->advanceRepository->delete($params);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.payroll_advance_salary.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }
    function show($id)
    {
        try {
            $data['title']         = 'Advance Show';
            $params                = [
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ];
            if (auth()->user()->role->slug == 'staff') {
                $params['user_id'] = auth()->user()->id;
            }
            $data['advance']       = $this->advanceRepository->model($params);
            return view('backend.payroll.advance.details', compact('data'));
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function approveModal($id)
    {
        try {
            $data['title']         = _trans('payroll.Approve');
            $params                = [
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ];
            if (auth()->user()->role->slug == 'staff') {
                $params['user_id'] = auth()->user()->id;
            }
            $data['approve']       = $this->advanceRepository->model($params);
            $data['url']           = (hasPermission('advance_salaries_approve')) ? route('hrm.payroll_advance_salary.approve', $id) : '';
            if (auth()->user()->role->slug == 'staff' && $data['approve']->status_id != 2) {
                $data['url'] = '';
            }
            $data['button']        = _trans('common.Approve');
            return view('backend.payroll.advance.approve_modal', compact('data'));
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    function approve(Request $request, $id)
    {
        try {
            $params                = [
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ];
            if (auth()->user()->role->slug == 'staff') {
                $params['user_id'] = auth()->user()->id;
            }
            $advance       = $this->advanceRepository->model($params);
            if (!$advance) {
                Toastr::error('Advance not found!', 'Error');
                return redirect()->route('hrm.payroll_advance_salary.index');
            }
            $result = $this->advanceRepository->approve($params, $request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.payroll_advance_salary.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function pay($id)
    {
        try {
            $data['title']         = _trans('payroll.Make Payment');
            $params                = [
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ];
            if (auth()->user()->role->slug == 'staff') {
                $params['user_id'] = auth()->user()->id;
            }
            $data['advance']       = $this->advanceRepository->model($params);
            $data['category'] = $this->incomeExpenseCategoryRepository->model(
                [
                    'is_income' => 0,
                    'status_id' => 1,
                    'company_id' => $this->companyRepository->company()->id
                ]
            )->get();
            $data['payment_method'] = DB::table('payment_methods')->where(
                [
                    'company_id' => $this->companyRepository->company()->id
                ]
            )->get();
            $data['url']           = (hasPermission('advance_salaries_pay')) ? route('hrm.payroll_advance_salary.pay_store', $id) : '';
            if (auth()->user()->role->slug == 'staff' && $data['advance']->status_id != 2) {
                $data['url'] = '';
            }
            $data['accounts']      = $this->accountRepository->model(
                [
                    'company_id' => $this->companyRepository->company()->id,
                    'status_id' => 1,
                ]
            )->get();
            return view('backend.payroll.advance.payment_modal', compact('data'));
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    function payStore(Request $request, $id)
    {
        if (!$request->category) {
            Toastr::error('Please select category!', 'Error');
            return redirect()->back();
        }
        if (!$request->account) {
            Toastr::error('Please select account!', 'Error');
            return redirect()->back();
        }
        try {
            $params                = [
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ];
            if (auth()->user()->role->slug == 'staff') {
                $params['user_id'] = auth()->user()->id;
            }
            $advance       = $this->advanceRepository->model($params);
            if (!$advance) {
                Toastr::error('Advance not found!', 'Error');
                return redirect()->route('hrm.payroll_advance_salary.index');
            }
            $result = $this->advanceRepository->pay($request, $params);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.payroll_advance_salary.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    function invoice($id)
    {

        try {
            $params                = [
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ];
            if (auth()->user()->role->slug == 'staff') {
                $params['user_id'] = auth()->user()->id;
            }
            $data['advance']       = $this->advanceRepository->model($params);
            return view('backend.payroll.advance.invoice', compact('data'));
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }
    public function advanceLoan($id)
    {
        try {
            $user = $this->user->getById($id);
            if ($user) {
                if (!myCompanyData($user->company_id)) {
                    Toastr::warning('You Can\'t access!', 'Access Denied');
                    return redirect()->back();
                }

                $data['id'] = $id;
                $data['title'] = _trans('payroll.Advance & Loan');
                $data['user'] = $this->user->getById($id);
                return $data;
                return view('backend.user.advance_loan', compact('data'));
            } else {
                Toastr::error('User Not Found.', 'Error');
                return back();
            }
        } catch (\Exception $exception) {
            Toastr::error('Something went wrong.', 'Error');
        }
    }

    public function userProfileTable(Request $request)
    {
        if ($request->ajax()) {
            return $this->advanceRepository->table($request);
        }
    }
}
