<?php

namespace App\Http\Controllers\Backend\Payroll;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Company\CompanyRepository;
use App\Repositories\Hrm\Payroll\SalaryRepository;
use App\Repositories\Hrm\Finance\AccountRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Repositories\Hrm\Department\DepartmentRepository;
use App\Repositories\Hrm\Expense\ExpenseCategoryRepository;

class SalaryController extends Controller
{
    use ApiReturnFormatTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $accountRepository;
    protected $salaryRepository;
    protected $department;
    protected $incomeExpenseCategoryRepository;
    protected $companyRepository;

    public function __construct(
        AccountRepository $accountRepository,
        SalaryRepository $salaryRepository,
        DepartmentRepository $department,
        ExpenseCategoryRepository $incomeExpenseCategoryRepository,
        CompanyRepository $companyRepository
    ) {
        $this->accountRepository  = $accountRepository;
        $this->salaryRepository  = $salaryRepository;
        $this->department        = $department;
        $this->incomeExpenseCategoryRepository = $incomeExpenseCategoryRepository;
        $this->companyRepository = $companyRepository;
    }

    function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $this->salaryRepository->table($request);
            }
            $data['class']  = 'salary_table';
            $data['fields'] = $this->salaryRepository->fields();
            $data['table']  = route('hrm.payroll_salary.index');
            $data['url_id']    = 'salary_table_url';

            $data['salary_generate']  = (hasPermission('salary_generate')) ? route('hrm.payroll_salary.generate_modal') : '';
            $data['title']            = _trans('payroll.Salary');
            $data['departments'] = $this->department->getAll();
            return view('backend.payroll.salary.index', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function datatable(Request $request)
    {
        return $this->salaryRepository->datatable($request);
    }

    public function generateModal()
    {
        try {
            $data['title']         = _trans('payroll.Salary Generate');
            $data['url']           = (hasPermission('salary_generate')) ? route('hrm.payroll_salary.generate') : '';
            $data['button']        = _trans('common.Generate');

            $data['departments'] = $this->department->getAll();
            return view('backend.payroll.salary.generate_modal', compact('data'));
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function generate(Request $request)
    {
        try {
            return $this->salaryRepository->generate($request);
        } catch (\Throwable $th) {
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    public function calculateModal($id)
    {
        try {
            $data['title']         = _trans('payroll.Salary Calculation');
            $data['url']           = (hasPermission('salary_calculate')) ? route('hrm.payroll_salary.calculate', $id) : '';
            $data['button']        = _trans('common.Generate');
            $params                = [
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ];

            $data['salary'] = $this->salaryRepository->model($params)->first();
            if ($data['salary']) {
                $data['info'] = $this->salaryRepository->info($params);
                return view('backend.payroll.salary.calculate_modal', compact('data'));
            } else {
                return response()->json('fail');
            }
        } catch (\Throwable $e) {
            return response()->json('fail');
        }
    }
    public function calculate(Request $request, $id)
    {
        try {
            $params                = [
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ];
            $result = $this->salaryRepository->calculate($request, $params);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.payroll_salary.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->route('hrm.payroll_salary.index');
            }
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->route('hrm.payroll_salary.index');
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
            $data['advance']       = $this->salaryRepository->model($params)->first();
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
            $data['url']           = (hasPermission('salary_pay')) ? route('hrm.payroll_salary.pay_store', $id) : '';
            if (auth()->user()->role->slug == 'staff' && $data['advance']->status_id != 2) {
                $data['url'] = '';
            }
            $data['accounts']      = $this->accountRepository->model(
                [
                    'company_id' => $this->companyRepository->company()->id,
                    'status_id' => 1,
                ]
            )->get();
            return view('backend.payroll.salary.payment_modal', compact('data'));
        } catch (\Throwable $e) {
            return response()->json('fail');
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
            $salary       = $this->salaryRepository->model($params)->first();
            if (!$salary) {
                Toastr::error('Salary not found!', 'Error');
                return redirect()->route('hrm.payroll_salary.index');
            }
            $result = $this->salaryRepository->pay($request, $salary);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.payroll_salary.index');
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
            $data['title'] = _trans('payroll.Payslip');
            $params                = [
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ];
            if (auth()->user()->role->slug == 'staff') {
                $params['user_id'] = auth()->user()->id;
            }
            $data['salary']       = $this->salaryRepository->model($params)->first();
            return view('backend.payroll.salary.payslip', compact('data'));
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    function show($id)
    {
        try {
            $data['title']     = _trans('payroll.Salary details');
            $params                = [
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ];
            if (auth()->user()->role->slug == 'staff') {
                $params['user_id'] = auth()->user()->id;
            }
            $data['salary']       = $this->salaryRepository->model($params)->first();
            return view('backend.payroll.salary.show', compact('data'));
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    function delete($id)
    {
        try {
            $result = $this->salaryRepository->delete($id, $this->companyRepository->company()->id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.payroll_salary.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->route('hrm.payroll_salary.index');
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->route('hrm.payroll_salary.index');
        }
    }

    public function userProfileTable(Request $request, $user_id)
    {
        if ($request->ajax()) {
            return $this->salaryRepository->table($request);
        }
    }
}
