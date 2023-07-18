<?php

namespace App\Repositories\Hrm\Finance;

use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Models\Finance\Account;
use App\Models\Finance\Deposit;
use App\Models\Finance\Transaction;
use Illuminate\Support\Facades\DB;

class DepositRepository
{

    use ApiReturnFormatTrait, FileHandler;
    protected $model;

    public function __construct(Deposit $model)
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
            _trans('account.ID'),
            _trans('account.Account'),
            _trans('account.Category'),
            _trans('account.Amount'),
            _trans('account.Date'),
            _trans('account.Payment Method'),
            _trans('account.Reference'),
            _trans('account.Action'),
        ];
    }

    public function datatable($request)
    {

        $content = $this->model->query()->with('transaction', 'category', 'payment')->where('company_id', auth()->user()->company_id)
            ->select('company_id', 'income_expense_category_id', 'date', 'payment_method_id', 'ref', 'id', 'transaction_id', 'created_at', 'amount');
        if ($request->account) {
            $content->whereHas('transaction', function ($query) use ($request) {
                $query->where('account_id', $request->account);
            });
        }
        return datatables()->of($content->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                if (hasPermission('deposit_edit')) {
                    $action_button .= '<a href="' . route('hrm.deposits.edit', $data->id) . '" class="dropdown-item"> ' . _trans('common.Edit') . '</a>';
                }
                if (hasPermission('deposit_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/deposit/delete/`)', 'delete');
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
            ->addColumn('account', function ($data) {
                return @$data->transaction->account->name;
            })
            ->addColumn('category', function ($data) {
                return @$data->category->name;
            })
            ->addColumn('payment', function ($data) {
                return $data->payment->name;
            })
            ->addColumn('date', function ($data) {
                return @$data->created_at->format('d-m-Y');
            })
            ->addColumn('amount', function ($data) {
                return currency_format(@$data->amount);
            })
            ->rawColumns(array('account', 'category', 'amount', 'date', 'payment', 'action'))
            ->make(true);
    }

    public function store($request)
    {

        DB::beginTransaction();
        try {

            $transaction = new Transaction;
            $transaction->account_id = $request->account;
            $transaction->company_id = auth()->user()->company->id;
            $transaction->date = $request->date;
            $transaction->description = $request->description;
            $transaction->amount = $request->amount;
            // $deposit->income_expense_category_id = $request->category;
            $transaction->transaction_type = 19;
            $transaction->status_id = 8;
            $transaction->created_by = auth()->id();
            $transaction->updated_by = auth()->id();
            $transaction->save();

            $deposit = new $this->model;
            $deposit->user_id = auth()->id();
            $deposit->income_expense_category_id = $request->category;
            $deposit->company_id = auth()->user()->company->id;
            $deposit->date = $request->date;
            $deposit->amount = $request->amount;
            $deposit->request_amount = $request->amount;
            $deposit->ref = $request->ref;
            $deposit->payment_method_id = $request->payment_method_id;
            $deposit->remarks = $request->description;
            $deposit->pay = 8;
            $deposit->status_id = 5;
            $deposit->approver_id = auth()->id();
            $deposit->created_by = auth()->id();
            $deposit->updated_by = auth()->id();
            if ($request->hasFile('attachment')) {
                $deposit->attachment = $this->uploadImage($request->attachment, 'deposit')->id;
            }
            $deposit->transaction_id = $transaction->id;
            $deposit->save();

            $account = Account::findOrFail($request->account);
            $account->amount = $account->amount + $transaction->amount;
            $account->save();

            DB::commit();

            return $this->responseWithSuccess(_trans('message.Deposit created successfully.'), $transaction);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }
    public function update($request, $id, $company_id)
    {

        DB::beginTransaction();
        try {

            $deposit = $this->model(['id' => $id, 'company_id' => $company_id])->first();
            if (!$deposit) {
                return $this->responseWithError(_trans('message.Deposit not found'), 'id', 404);
            }
            if ($deposit->amount != $request->amount) {
                $account = Account::findOrFail($request->account);
                $account->amount = $account->amount - ($deposit->amount - $request->amount);
                $account->save();
            }
            $deposit->income_expense_category_id = $request->category;
            $deposit->date = $request->date;
            $deposit->amount = $request->amount;
            $deposit->request_amount = $request->amount;
            $deposit->ref = $request->ref;
            $deposit->payment_method_id = $request->payment_method_id;
            $deposit->remarks = $request->description;
            $deposit->approver_id = auth()->id();
            $deposit->updated_by = auth()->id();
            if ($request->hasFile('attachment')) {
                if ($deposit->attachment) {
                    $this->deleteImage(asset_path($deposit->attachment));
                    $deposit->attachmentFile->delete();
                }
                $deposit->attachment = $this->uploadImage($request->attachment, 'deposit')->id;
            }
            $deposit->save();

            $transaction = Transaction::find($deposit->transaction_id);
            $transaction->account_id = $request->account;
            $transaction->company_id = auth()->user()->company->id;
            $transaction->date = $request->date;
            $transaction->description = $request->description;
            $transaction->amount = $request->amount;
            $transaction->updated_by = auth()->id();
            $transaction->save();

            DB::commit();

            return $this->responseWithSuccess(_trans('message.Deposit update successfully.'), $transaction);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    public function delete($id, $company_id)
    {
        $deposit = $this->model(['id' => $id, 'company_id' => $company_id])->first();
        if (!$deposit) {
            return $this->responseWithError(_trans('Data not found'), 'id', 404);
        }
        DB::beginTransaction();
        try {
            $transaction = Transaction::find($deposit->transaction_id);
            if ($deposit->attachment) {
                $this->deleteImage(asset_path($deposit->attachment));
            }
            $deposit->attachmentFile->delete();
            $deposit->delete();
            $transaction->delete();

            DB::commit();

            return $this->responseWithSuccess(_trans('message.Deposit delete successfully.'), $deposit);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    // new Functions
    public function table($request)
    {
        {
            
            $data = $this->model->query()->with('transaction', 'category', 'payment')->where('company_id', auth()->user()->company_id)
                ->select('company_id', 'income_expense_category_id', 'date', 'payment_method_id', 'ref', 'id', 'transaction_id', 'created_at', 'amount');
            if ($request->account) {
                $data->whereHas('transaction', function ($query) use ($request) {
                    $query->where('account_id', $request->account);
                });
            }
            if (@$request->from && @$request->to) {
                $data = $data->whereBetween('created_at', start_end_datetime($request->from, $request->to));
            }
            if ($request->search) {
                $data->whereHas('category', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                });
            }
            $data = $data->paginate($request->limit ?? 2);
            return [
                'data' => $data->map(function ($data) {
                    $action_button = '';
                    if (hasPermission('deposit_edit')) {
                        $action_button .= '<a href="' . route('hrm.deposits.edit', $data->id) . '" class="dropdown-item"> ' . _trans('common.Edit') . '</a>';
                    }
                    if (hasPermission('deposit_delete')) {
                        $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/deposit/delete/`)', 'delete');
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
                        'account' => @$data->transaction->account->name,
                        'category' => @$data->category->name,
                        'amount' => showAmount(@$data->amount),
                        'date' => showDate(@$data->date),
                        'payment' => @$data->payment->name,
                        'ref' => @$data->ref,
                        'action' => $button,
                    ];
                }),
                'pagination' => [
                    'total' => $data->total(),
                    'count' => $data->count(),
                    'per_page' => $data->perPage(),
                    'current_page' => $data->currentPage(),
                    'total_pages' => $data->lastPage(),
                    'pagination_html' => $data->links('backend.pagination.custom')->toHtml(),
                ],
            ];
        }

    }

    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $deposit = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                return $this->responseWithSuccess(_trans('message.Deposit delete successfully.'), $deposit);
            } else {
                return $this->responseWithError(_trans('message.Deposit not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }

    }
}
