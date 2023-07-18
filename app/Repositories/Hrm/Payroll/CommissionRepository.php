<?php

namespace  App\Repositories\Hrm\Payroll;

use Validator;
use Illuminate\Http\JsonResponse;
use App\Models\Payroll\Commission;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class CommissionRepository
{
    use ApiReturnFormatTrait, RelationshipTrait;
    protected $model;

    public function __construct(Commission $model)
    {
        $this->model = $model;
    }

    public function model($filter = null)
    {
        $model = $this->model;
        if ($filter) {
            $model = $this->model->where($filter)->first();
        }
        return $model;
    }



    public function store($request)
    {
        $commission = $this->model->where('name', $request->name)->where('company_id', auth()->user()->company_id)->first();
        if ($commission) {
            return $this->responseWithError(_trans('Data already exists'), 'name', 422);
        }
        try {
            $commission = new $this->model;
            $commission->name = $request->name;
            $commission->type = $request->type;
            $commission->company_id = auth()->user()->company->id;
            $commission->save();
            return $this->responseWithSuccess(_trans('message.Commission created successfully.'), $commission);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function datatable()
    {
        $content = $this->model->query()->where('company_id', auth()->user()->company_id);
        return datatables()->of($content->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                $row_data = [];
                $row_data['id'] = $data->id;
                $row_data['name'] = $data->name;
                $row_data['type'] = $data->type;
                $row_data['status_id'] = $data->status_id;
                if (hasPermission('edit_payroll_item')) {
                    $action_button .= '<a href="' . route('hrm.payroll_items.edit', $data->id) . '" class="dropdown-item"> ' . _trans('common.Edit') . '</a>';
                }

                if (hasPermission('delete_payroll_item')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/payroll/item/delete/`)', 'delete');
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
            ->addColumn('type', function ($data) {
                if ($data->type == 1) {
                    return '<span class="badge badge-success">' . _trans('payroll.Addition') . '</span>';
                } else {
                    return '<span class="badge badge-danger">' . _trans('payroll.Deduction') . '</span>';
                }
                return @$data->type;
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->rawColumns(array('name', 'type', 'status', 'action'))
            ->make(true);
    }

    public function update($request, $id, $company_id)
    {
        $commission = $this->model(['id' => $id, 'company_id' => $company_id]);
        if (!$commission) {
            return $this->responseWithError(_trans('Data not found'), 'id', 404);
        }
        try {
            $commission->name = $request->name;
            $commission->type = $request->type;
            $commission->status_id = $request->status;
            $commission->save();
            return $this->responseWithSuccess(_trans('message.Commission Update successfully.'), $commission);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function delete($id, $company_id)
    {
        $commission = $this->model(['id' => $id, 'company_id' => $company_id]);
        if (!$commission) {
            return $this->responseWithError(_trans('Data not found'), 'id', 404);
        }
        try {
            $commission->delete();
            return $this->responseWithSuccess(_trans('message.Commission Delete successfully.'), $commission);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function getItemList($where = [])
    {
        return  $this->model->query()->where($where)->orderBy('id', 'desc')->get();
    }

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Name'),
            _trans('common.Type'),
            _trans('common.Status'),
            _trans('common.Action')

        ];
    }

    function table($request)
    {
        
        $data =  $this->model->query()->with('status')
            ->where('company_id', auth()->user()->company_id);
        $where = array();
        if ($request->search) {
            $where[] = ['name', 'like', '%' . $request->search . '%'];
        }
        $data = $data
            ->where($where)
            ->orderBy('id', 'desc')
            ->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('edit_payroll_item')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('hrm.payroll_items.edit', $data->id) . '`)', 'modal');
                }
                if (hasPermission('delete_payroll_item')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/payroll/item/delete/`)', 'delete');
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
                    'name' => $data->name,
                    'type' => @$data->type == 1 ? '<span class="badge badge-success">' . _trans('payroll.Addition') . '</span>' : '<span class="badge badge-danger">' . _trans('payroll.Deduction') . '</span>',
                    'status' => '<small class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</small>',
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

    // statusUpdate
    public function statusUpdate($request)
    {
        try {
            
            if (@$request->action == 'active') {
                $commission = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Commission activate successfully.'), $commission);
            }
            if (@$request->action == 'inactive') {
                $commission = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Commission inactivate successfully.'), $commission);
            }
            return $this->responseWithError(_trans('message.Commission failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $commission = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                return $this->responseWithSuccess(_trans('message.Commission Delete successfully.'), $commission);
            } else {
                return $this->responseWithError(_trans('message.Commission not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    //new functions

    function createAttributes()
    {
        return [
            'name' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'name',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Name')
            ],
            'type' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'type',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Type'),
                'options' => [
                    [
                        'text' => _trans('payroll.Addition'),
                        'value'  => 1,
                        'active' => true,
                    ],
                    [
                        'text' => _trans('payroll.Deduction'),
                        'value'  => 2,
                        'active' => false,
                    ]
                ]
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
    function editAttributes($commissionModel)
    {
        return [
            'name' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'name',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Name'),
                'value' => @$commissionModel->name,
            ],
            'type' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'type',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Type'),
                'options' => [
                    [
                        'text' => _trans('payroll.Addition'),
                        'value'  => 1,
                        'active' => $commissionModel->type == 1 ? true : false,
                    ],
                    [
                        'text' => _trans('payroll.Deduction'),
                        'value'  => 2,
                        'active' =>  $commissionModel->type == 2 ? true : false,
                    ]
                ]
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
                        'active' => $commissionModel->status_id == 1 ? true : false,
                    ],
                    [
                        'text' => _trans('payroll.Inactive'),
                        'value'  => 4,
                        'active' =>  $commissionModel->status_id == 4 ? true : false,
                    ]
                ]
            ]

        ];
    }
}
