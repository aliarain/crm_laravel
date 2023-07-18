<?php

namespace  App\Repositories\Hrm\Finance;

use App\Models\Finance\Account;
use Illuminate\Http\JsonResponse;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class AccountRepository
{

    use ApiReturnFormatTrait;
    protected $model;

    public function __construct(Account $model)
    {
        $this->model = $model;
    }

    function fields()
    {
        return [
            _trans('account.ID'),
            _trans('account.Name'),
            _trans('account.Balance'),
            _trans('account.Account Name'),
            _trans('account.Account Number'),
            _trans('account.Branch'),
            _trans('account.Status'),
            _trans('account.Action'),
        ];
    }


    public function model($filter = null)
    {
        $model = $this->model;
        if ($filter) {
            $model = $this->model->where($filter);
        }
        return $model;
    }

    public function store($request)
    {
        $account = $this->model->where('name', $request->name)->first();
        if ($account) {
            return $this->responseWithError(_trans('Account already exists'), 'name', 422);
        }
        try {
            $account             = new $this->model;
            $account->name       = $request->name;
            $account->amount     = $request->balance;
            $account->ac_name    = $request->ac_name;
            $account->ac_number  = $request->ac_number;
            $account->code       = $request->code;
            $account->branch     = $request->branch;
            $account->status_id  = $request->status;
            $account->company_id = auth()->user()->company->id;
            $account->save();
            return $this->responseWithSuccess(_trans('message.Account created successfully.'), $account);
        } catch (\Throwable $th) {
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    public function datatable()
    {
        $content = $this->model->with('status')->where('company_id', auth()->user()->company_id);
        return datatables()->of($content->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                if (hasPermission('account_edit')) {
                    $action_button .= '<a href="' . route('hrm.accounts.edit', $data->id) . '" class="dropdown-item"> ' . _trans('common.Edit') . '</a>';
                }
                if (hasPermission('account_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/accounts/delete/`)', 'delete');
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
            ->addColumn('name', function ($data) {
                return @$data->name;
            })
            ->addColumn('amount', function ($data) {
                return currency_format(@$data->amount);
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->rawColumns(array('name', 'status', 'action'))
            ->make(true);
    }

    public function update($request, $id, $company_id)
    {
        $account = $this->model(['id' => $id, 'company_id' => $company_id])->first();
        if (!$account) {
            return $this->responseWithError(_trans('Account not found'), 'id', 404);
        }
        try {
            $account->name       = $request->name;
            $account->amount     = $request->balance;
            $account->ac_name    = $request->ac_name;
            $account->ac_number  = $request->ac_number;
            $account->code       = $request->code;
            $account->branch     = $request->branch;
            $account->status_id  = $request->status;
            $account->save();
            return $this->responseWithSuccess(_trans('message.Account update successfully.'), $account);
        } catch (\Throwable $th) {
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    function delete($id, $company_id)
    {
        $account = $this->model(['id' => $id, 'company_id' => $company_id]);
        if (!$account) {
            return $this->responseWithError(_trans('Data not found'), 'id', 404);
        }
        try {
            $account->delete();
            return $this->responseWithSuccess(_trans('message.Account delete successfully.'), $account);
        } catch (\Throwable $th) {
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    //new functions
    function table($request)
    {
        
        $params = [];
        if (@$request->user_id) {
            $params['id'] = $request->user_id;
        }

        if (@$request->department) {
            $params['department_id'] = $request->department;
        }
        $data = $this->model->with('status')
            ->where('company_id', auth()->user()->company_id)
            ->where($params);
        if (@$request->from && @$request->to) {
            $data = $data->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if ($request->search) {
            $data = $data->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('ac_name', 'like', '%' . $request->search . '%');
        }
        $data = $data->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('account_edit')) {
                    $action_button .= '<a href="' . route('hrm.accounts.edit', $data->id) . '" class="dropdown-item">  <span class="icon mr-8"><i class="fa-solid fa-pen-to-square"></i></span>' . _trans('common.Edit') . '</a>';
                }
                if (hasPermission('account_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/accounts/delete/`)', 'delete');
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

                    'id'         => $data->id,
                    'name'       => $data->name,
                    'balance'       => showAmount(@$data->amount),
                    'ac_name'       => @$data->ac_name,
                    'ac_number'       => @$data->ac_number,
                    'branch'       => @$data->branch,
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

    // statusUpdate
    public function statusUpdate($request)
    {
        try {
            
            if (@$request->action == 'active') {
                $accounts = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Account activate successfully.'), $accounts);
            }
            if (@$request->action == 'inactive') {
                $accounts = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Account inactivate successfully.'), $accounts);
            }
            return $this->responseWithError(_trans('message.Account inactivate failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $accounts = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['deleted_at' => now()]);
                return $this->responseWithSuccess(_trans('message.Account Delete successfully.'), $accounts);
            } else {
                return $this->responseWithError(_trans('message.Account not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
