<?php

namespace App\Http\Controllers\Backend\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\Company\CompanyRepository;
use App\Repositories\Hrm\Finance\AccountRepository;

class PayrollAccountController extends Controller
{

    protected $accountRepository;
    protected $companyRepository;

    public function __construct(
        AccountRepository $accountRepository,
        CompanyRepository $companyRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->companyRepository = $companyRepository;
    }

    function index(Request $request)
    {

        if ($request->ajax()) {
            return $this->accountRepository->table($request);
        }
        $data['checkbox'] = true;
        $data['title'] = _trans('common.Accounts');
        $data['fields'] = $this->accountRepository->fields();
        $data['class'] = 'accounts_datatable';
        return view('backend.finance.accounts.index', compact('data'));
    }

    function datatable()
    {
        return $this->accountRepository->datatable();
    }

    public function create()
    {
        $data['title']         = 'Account Create';
        $data['url']           = (hasPermission('account_store')) ? route('hrm.accounts.store') : '';
        return view('backend.finance.accounts.create', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:191|',
            'status' => 'required:max:191',
            'ac_name' => 'required|max:191',
            'ac_number' => 'required|numeric|min:2',
            'branch' => 'required|max:191',
            'balance' => 'required|max:191',
            'code' => 'required|max:191',
        ]);


        try {
            $result = $this->accountRepository->store($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.accounts.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $data['edit']  = $this->accountRepository->model([
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ])->first();
            $data['title']        =  _trans('payroll.Edit Account');
            $data['url']          = (hasPermission('account_update')) ? route('hrm.accounts.update', [$data['edit']->id, $data['edit']->company_id]) : '';
            $data['is_permission']  = (hasPermission('account_update')) ? true : false;
            return view('backend.finance.accounts.create', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id, $company_id)
    {
        $request->validate([
            'name' => 'required|max:191|',
            'status' => 'required:max:191',
            'ac_name' => 'required|max:191',
            'ac_number' => 'required|numeric|min:2',
            'branch' => 'required|max:191',
            'balance' => 'required|max:191',
            'code' => 'required|max:191',
        ]);

        try {
            $result = $this->accountRepository->update($request, $id, $company_id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.accounts.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->accountRepository->delete($id, $this->companyRepository->company()->id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.accounts.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->route('hrm.accounts.index');
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->route('hrm.accounts.index');
        }
    }

    public function balanceSheet()
    {
    }

    // status change
    public function statusUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        return $this->accountRepository->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->accountRepository->destroyAll($request);
    }
}
