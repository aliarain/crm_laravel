<?php

namespace App\Http\Controllers\Backend\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\Company\CompanyRepository;
use App\Repositories\Hrm\Finance\AccountRepository;
use App\Repositories\Hrm\Finance\TransactionRepository;
use App\Repositories\Hrm\Expense\ExpenseCategoryRepository;

class TransactionController extends Controller
{
    protected $transactionRepository;
    protected $accountRepository;

    public function __construct(
        TransactionRepository $transactionRepository,
        AccountRepository $accountRepository
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
    }

    function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->transactionRepository->table($request);
        }
        $data['checkbox'] = true;
        $data['class'] = 'transaction_datatable';

        $data['title']          = _trans('common.Transaction History');
        $data['fields']         = $this->transactionRepository->fields('transaction');
        $data['accounts']       = $this->accountRepository->model([
            'company_id' => auth()->user()->company_id,
        ])->get();
        return view('backend.finance.transaction.index', compact('data'));
    }

    function datatable(Request $request)
    {
        return $this->transactionRepository->datatable($request, 'transaction');
    }
}
