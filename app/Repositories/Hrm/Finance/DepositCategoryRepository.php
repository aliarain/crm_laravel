<?php

namespace  App\Repositories\Hrm\Finance;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CoreApp\Traits\FileHandler;
use BeyondCode\QueryDetector\Outputs\Console;
use App\Models\Expenses\IncomeExpenseCategory;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class DepositCategoryRepository.
 */
class DepositCategoryRepository
{
    use RelationshipTrait, FileHandler, ApiReturnFormatTrait;

    /**
     * @return string
     *  Return the model
     */

    public function __construct(IncomeExpenseCategory $model)
    {
        $this->model = $model;
    }

    public function model($filter = null)
    {
        $model = $this->model;
        if (@$filter) {
            $model = $this->model->where($filter);
        }
        return $model;
    }

    function fields()
    {
        return [
            _trans('account.ID'),
            _trans('account.Name'),
            _trans('account.Status'),
            _trans('account.Action'),
        ];
    }


    public function datatable($type)
    {
        if ($type == 'deposit') {
            $content = $this->model->query()->where('is_income', 1)->where('company_id', Auth::user()->company_id);
        } elseif ($type == 'expense') {
            $content = $this->model->query()->where('is_income', 0)->where('company_id', Auth::user()->company_id);
        }
        if (isset($params)) {
            $content = $content->where($params);
        }
        return datatables()->of($content->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                if (hasPermission('deposit_category_update')) {
                    $action_button .= '<a href="' . route('hrm.deposit_category.edit', $data->id . '?type=' . $data->is_income) . '" class="dropdown-item"> Edit</a>';
                }
                if (hasPermission('deposit_category_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(`' . $data->id . '?type=' . $data->is_income . '`,`hrm/account-settings/delete/`)', 'delete');
                }
                $button = getActionButtons($action_button);
                return $button;
            })
         
            ->addColumn('name', function ($data) {
                return @$data->name;
            })
          
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
        
            ->rawColumns(array('name', 'status', 'action'))
            ->make(true);
    }

    public function store($request)
    {
        $category = $this->model([
            'name' => $request->name,
            'company_id' => auth()->user()->company_id,
            'is_income' => intval($request->is_income) ? 1 : 0,
        ])->first();
        if ($category) {
            return $this->responseWithError(_trans('message.Category already exists'), 'name', 422);
        }
        try {
            $category             = new $this->model;
            $category->company_id = auth()->user()->company_id;
            $category->name       = $request->name;
            $category->is_income  = $request->is_income ? 1 : 0;
            $category->status_id  = $request->status;
            $category->save();
            return $this->responseWithSuccess(_trans('message.Category created successfully.'), $category);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    public function update($request, $id, $company_id)
    {
        $category = $this->model([
            'id' => $id,
            'company_id' => $company_id
        ])->first();
        if (!$category) {
            return $this->responseWithError(_trans('message.Category not found'), 'id', 404);
        }
        try {
            $category->name       = $request->name;
            $category->is_income  = $request->is_income;
            $category->status_id  = $request->status;
            $category->save();
            return $this->responseWithSuccess(_trans('message.Category update successfully.'), $category);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function delete($id, $company_id)
    {
        $category = $this->model([
            'id' => $id,
            'company_id' => $company_id
        ])->first();
        if (!$category) {
            return $this->responseWithError(_trans('message.Category not found'), 'id', 404);
        }

        try {
            $category->delete();
            return $this->responseWithSuccess(_trans('message.Category delete successfully.'), $category);
        } catch (\Throwable $th) {
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    // new Functions
    function deposit_table($request)
    { {
            
            $data = $this->model->query()->where('company_id', Auth::user()->company_id)->where('is_income', @$request->is_income ?? 0);
            if (@$request->from && @$request->to) {
                $data = $data->whereBetween('created_at', start_end_datetime($request->from, $request->to));
            }
            if ($request->search) {
                $data = $data->where('name', 'like', '%' . $request->search . '%');
            }
            $data = $data->orderBy('id', 'desc')->paginate($request->limit ?? 2);
            return [
                'data' => $data->map(function ($data) {
                    $action_button = '';
                    if (hasPermission('deposit_category_update')) {
                        $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('hrm.deposit_category.edit', $data->id . '?type=' . $data->is_income) . '`)', 'modal');
                    }
                    if (hasPermission('deposit_category_delete')) {
                        $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(`' . $data->id . '?type=' . $data->is_income . '`,`hrm/account-settings/delete/`)', 'delete');
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
                        'name' => @$data->name,
                        'status' => '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>',
                        'action'   => $button
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

    // statusUpdate
    public function statusUpdate($request)
    {
        try {
            if (@$request->action == 'active') {
                $category = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Category activate successfully.'), $category);
            }
            if (@$request->action == 'inactive') {
                $category = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Category inactivate successfully.'), $category);
            }
            return $this->responseWithError(_trans('message.Category failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $category = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                return $this->responseWithSuccess(_trans('message.Category delete successfully.'), $category);
            } else {
                return $this->responseWithError(_trans('message.Category not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function createAttributes($type)
    {
        return [
            'is_income' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'is_income',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group d-none',
                'label' => _trans('common.is_income'),
                'value' => $type
            ],
            'name' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'name',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Name')
            ],
            'status' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'status',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Status'),
                'options' => [
                    [
                        'text' => _trans('payroll.Active'),
                        'value'  => 1,
                        'active' => true,
                    ],
                    [
                        'text' => _trans('payroll.Inactive'),
                        'value'  => 4,
                        'active' => false,
                    ]
                ]
            ]

        ];
    }
    function editAttributes($department, $type)
    {
        return [
            'is_income' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'is_income',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group d-none',
                'label' => _trans('common.is_income'),
                'value' => $type
            ],
            'name' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'name',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Name'),
                'value' => @$department->name,
            ],
            'status' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'status',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Status'),
                'options' => [
                    [
                        'text' => _trans('payroll.Active'),
                        'value'  => 1,
                        'active' => $department->status_id == 1 ? true : false,
                    ],
                    [
                        'text' => _trans('payroll.Inactive'),
                        'value'  => 4,
                        'active' =>  $department->status_id == 4 ? true : false,
                    ]
                ]
            ]

        ];
    }
}
