<?php

namespace App\Http\Controllers\Backend\Payroll;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\ProfileRepository;
use App\Models\Payroll\SalarySetupDetails;
use App\Repositories\Company\CompanyRepository;
use App\Http\Requests\Payroll\CommissionSetRequest;
use App\Repositories\Hrm\Payroll\CommissionRepository;
use App\Repositories\Hrm\Payroll\PayrollSetUpRepository;
use App\Repositories\Hrm\Department\DepartmentRepository;

class SalarySetUpController extends Controller
{
    protected $view_path = 'backend.payroll.setup';
    protected $payrollSetupRepository;
    protected $companyRepository;
    protected $department;
    protected $profile;
    protected $commissionRepository;

    public function __construct(
        PayrollSetUpRepository $payrollSetupRepository,
        CompanyRepository $companyRepository,
        DepartmentRepository $department,
        ProfileRepository     $profileRepository,
        CommissionRepository $commissionRepository
    ) {
        $this->payrollSetupRepository = $payrollSetupRepository;
        $this->companyRepository = $companyRepository;
        $this->commissionRepository = $commissionRepository;
        $this->department = $department;
        $this->profile = $profileRepository;
    }

    // payroll setup index
    public function index(Request $request)
    {

        try {
            if ($request->ajax()) {
                return $this->payrollSetupRepository->table($request);
            }
            $data['class']  = 'salary_set_up_table';
            $data['fields'] = $this->payrollSetupRepository->fields();

            $data['table'] = route('hrm.payroll_setup.index');
            $data['url_id'] = 'salary_set_up_url';

            $data['title']          =  _trans('payroll.Employee Setup List');
            $data['departments'] = $this->department->getAll();
            // echo "<pre>";print_r($this->view_path);exit;
            return view($this->view_path . '.index', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function data(Request $request)
    {
        return $this->payrollSetupRepository->datatable($this->companyRepository->company()->id, $request);
    }

    public function setup($id)
    {
        try {
            $data['title']       = _trans('payroll.Payroll Setup');
            $set_salary          = $this->payrollSetupRepository->getSalaryInfo($id, $this->companyRepository->company()->id);
            if (!$set_salary) {
                Toastr::success($set_salary->original['message'], 'Success');
                return redirect()->back();
            }
            $data['set_salary']  = $set_salary;
            return view($this->view_path . '.salary_set', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function profileSetUp(Request $request, $user_id, $slug)
    {
        $user = User::find($user_id);
        $data['slug'] = $slug;
        $data['id'] = $user->id;
        $request['user_id'] = $user->id;
        $data['show'] = $this->profile->getProfile($request, $slug);
        $data['title'] = _trans('common.Contract Setup');
        return view('backend.payroll.setup.set_contract', compact('data'));
    }

    public function profileSetUpdate(Request $request, $user_id, $slug)
    {
        try {
            $update = $this->profile->updateProfile($request, $slug);
            if ($update) {
                Toastr::success(_trans('response.Operation successful'), 'Success');
                return redirect()->back();
            } else {
                Toastr::error(_trans('response.Something went wrong.'), 'Error');
                return redirect()->back();
            }
        } catch (\Exception $exception) {
            Toastr::error('Something went wrong.', 'Error');
            return back();
        }
    }



    public function store_salary_setup(Request $request)
    {
        $request->validate([
            'type' => 'required:max:191',
            'amount' => 'required:max:191',
        ]);
        $request->merge(['company_id' => $this->companyRepository->company()->id]);
        try {
            $result = $this->payrollSetupRepository->store($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->back();
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function edit_salary_setup($id)
    {
        $payrollSetupRepository = $this->payrollSetupRepository->salarySetup([
            'company_id' => $this->companyRepository->company()->id,
            'id' => $id
        ]);
        $data['title']          =  _trans('payroll.Update Commission');
        $data['type']         = $payrollSetupRepository->commission->type;
        $data['user_id']      = $payrollSetupRepository->user_id;
        $data['list']         = $this->commissionRepository->getItemList([
            'company_id' => $this->companyRepository->company()->id,
            'type'    => $data['type']
        ]);
        $data['url']          = route('hrm.payroll_setup.update_salary_setup', $id);
        $data['id']           = $id;
        $data['repository']   = $payrollSetupRepository;
        return view('backend.payroll.setup.commission_modal', compact('data'));
    }

    function update_salary_setup(Request $request, $id)
    {
        $request->validate([
            'type' => 'required:max:191',
            'amount' => 'required:max:191'
        ]);
        try {
            $result = $this->payrollSetupRepository->salary_details_update($request, $id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->back();
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function commissionSetUp(Request $request, $user_id)
    {
        try {

            $data['title']          =  _trans('payroll.Commission Setup');

            if ($request->ajax()) {
                return $this->payrollSetupRepository->userCommissionTable($request, $user_id);
            }
            $data['fields'] = $this->payrollSetupRepository->setCommissionFields();
            $data['checkbox'] = true;
            $data['delete_url']    = route('payroll_items.delete_data');
            $data['status_url']    = route('payroll_items.statusUpdate');
            $data['create_url']    = route('hrm.payroll_setup.item_list', 'user=' . $user_id);

            $data['table']     = route('hrm.payroll_setup.user_commission_setup', $user_id);
            $data['url_id']    = 'payroll_item_set_up_table_url';
            $data['class']     = 'table_class';


            return view('backend.payroll.setup.set_commission', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
    public function item_list_create_modal(Request $request)
    {

        try {
            $commissionModel        = $this->commissionRepository->getItemList([
                'company_id' => $this->companyRepository->company()->id
            ]);
            $data['title']        = _trans('payroll.Add Commission');
            $data['url']          = route('hrm.payroll_setup.create_user_commission_setup', $request->user);
            $data['attributes'] = $this->payrollSetupRepository->createSetCommissionAttributes($commissionModel);
            @$data['button']   = _trans('common.Add');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }


    public function create_user_commission_setup(CommissionSetRequest $request, $user_id)
    {

        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->payrollSetupRepository->create_user_commission_setup($request, $user_id);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function item_list_edit_modal(Request $request, $id)
    {

        try {
            $commissionModel = SalarySetupDetails::where(['id' => $id, 'company_id' => $this->companyRepository->company()->id])->first();
            $list        = $this->commissionRepository->getItemList([
                'company_id' => $this->companyRepository->company()->id
            ]);
            $data['title']        = _trans('payroll.Add Commission');
            $data['url']          = route('hrm.payroll_setup.update_user_commission_setup', $id);
            $data['attributes'] = $this->payrollSetupRepository->editSetCommissionAttributes($commissionModel, $list);
            @$data['button']   = _trans('common.Update');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function update_user_commission_setup(CommissionSetRequest $request, $id)
    {

        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->payrollSetupRepository->update_user_commission_setup($request, $id);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function userProfileTable(Request $request, $user_id)
    {
        if ($request->ajax()) {
            return $this->payrollSetupRepository->userCommissionTable($request, $user_id);
        }
    }
}
