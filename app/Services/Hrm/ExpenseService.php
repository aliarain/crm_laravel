<?php

namespace App\Services\Hrm;

use App\Services\Core\BaseService;
use App\Models\Expenses\HrmExpense;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\CurrencyTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class ExpenseService extends BaseService
{
    use RelationshipTrait, DateHandler, CurrencyTrait;

    public function __construct(HrmExpense $hrmExpense)
    {
        $this->model = $hrmExpense;
    }

    public function dataTable()
    {
        $expenses = $this->model->query()
            ->where('company_id', $this->companyInformation()->id)
            ->select('id', 'date', 'user_id', 'amount', 'status_id', 'is_claimed_status_id', 'attachment_file_id')
            ->where('is_claimed_status_id', 11);

        $expenses->when(\request()->get('date'), function (Builder $builder) {
            $date = explode(' - ', \request()->get('date'));
            return $builder->whereBetween('date', [$this->databaseFormat($date[0]), $this->databaseFormat($date[1])]);
        });
        $expenses->when(\request()->get('user_ids'), function (Builder $builder) {
            return $builder->whereIn('user_id', \request()->get('user_ids'));
        });

        $expenses->when(\request()->get('category_ids'), function (Builder $builder) {
            return $builder->whereIn('expense_category_id', \request()->get('category_ids'));
        });
        return datatables()->of($expenses->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                $reject = _trans('common.Reject');
                $approve = _trans('common.Approve');
                $delete = _trans('common.Delete');
                if (hasPermission('department_update')) {
                    if ($data->status_id == 1) {
                        $action_button .= actionButton($reject, 'ApproveOrReject(' . $data->id . ',' . "6" . ',`hrm/expense/approve-or-reject/`,`Approve`)', 'approve');
                    }
                    if ($data->status_id == 6) {
                        $action_button .= actionButton($approve, 'ApproveOrReject(' . $data->id . ',' . "1" . ',`hrm/expense/approve-or-reject/`,`Approve`)', 'approve');
                    }
                }
                if (hasPermission('department_delete')) {
                    $action_button .= actionButton($delete, '__globalDelete(' . $data->id . ',`hrm/department/delete/`)', 'delete');
                }
                $button = getActionButtons($action_button);
                return $button;
            })
            ->addColumn('date', function ($data) {
                return @$data->date;
            })
            ->addColumn('file', function ($data) {
                if ($data->attachment_file_id != null) {
                    return '<a href="' . uploaded_asset($data->attachment_file_id) . '" download class="btn btn-white btn-sm"><i class="fas fa-download"></i></a>';
                } else {
                    return _trans('common.No File');
                }
            })
            ->addColumn('employee_name', function ($data) {
                return @$data->user->name;
            })
            ->addColumn('amount', function ($data) {
                return $this->getCurrency() . $data->amount;
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->rawColumns(array('date', 'employee_name', 'amount', 'status', 'file', 'action'))
            ->make(true);
    }

    public function approveOrReject($expense, $status)
    {
        $expense->status_id = $status;
        $expense->save();
        return true;
    }

    public function UserExpenseStore($request)
    {
        return $request; 
    }

    // new functions

    function paymentTable($request)
    {

        $data = $this->model->query()->where('company_id', $this->companyInformation()->id)
                ->select('id', 'date', 'user_id', 'amount', 'status_id', 'is_claimed_status_id', 'attachment_file_id','income_expense_category_id')
                ->where('is_claimed_status_id', 11);
        if (!is_Admin()) {
            $data = $data->where('user_id', auth()->id());
        }
        if ($request->from && $request->to) {
            $data = $data->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if ($request->search) {
            $data = $data->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->user_id) {
            $data = $data->where('user_id', $request->user_id);
        }
        if ($request->category_id) {
            $data = $data->where('income_expense_category_id', $request->category_id);
        }
        $data = $data->orderBy('id', 'DESC')->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('department_update')) {
                    if ($data->status_id == 1) {
                        $action_button .= actionButton(_trans('common.Reject'), 'ApproveOrReject(' . $data->id . ',' . "6" . ',`hrm/expense/approve-or-reject/`,`Approve`)', 'approve');
                    }
                    if ($data->status_id == 6) {
                        $action_button .= actionButton(_trans('common.Approve'), 'ApproveOrReject(' . $data->id . ',' . "1" . ',`hrm/expense/approve-or-reject/`,`Approve`)', 'approve');
                    }
                }
                if (hasPermission('department_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/department/delete/`)', 'delete');
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
                    'date'       => showDate($data->date),
                    'employee_name'   => $data->user->name,
                    'file'       => $data->attachment_file_id ? '<a href="' . uploaded_asset($data->attachment_file_id) . '" download class="btn btn-white btn-sm"><i class="fas fa-download"></i></a>' : _trans('common.No File'),
                    'amount'     => showAmount(@$data->amount),
                    'status'     => '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>',
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
