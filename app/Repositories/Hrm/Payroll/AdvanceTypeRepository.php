<?php

namespace  App\Repositories\Hrm\Payroll;

use Illuminate\Http\JsonResponse;
use Validator;
use App\Models\Payroll\AdvanceType;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class AdvanceTypeRepository
{
    use ApiReturnFormatTrait;
    protected $model;

    public function __construct(AdvanceType $model)
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

    public function getTypes(){
        return $this->model()->where('status_id',1)->get();
    }

    public function filter()
    {
        $items = $this->model->query()
            ->with('createdBy', 'updatedBy');
        $items = $this->FilterWhenQuery($items);
        if (\request()->get('published_filtering') === '1') {
            $items = $items->where('status_id', 1);
        } elseif (\request()->get('published_filtering') === '0') {
            $items = $items->where('status_id', 4);
        } else {
            return $items;
        }
        return $items;
    }

    public function FilterWhenQuery($query)
    {
        return $query->when(\request()->get('search'), function (Builder $builder) {
            $builder->where('name', 'like', '%' . \request()->get('search') . '%');
        })
            ->when(\request()->get('sort_by'), function (Builder $builder) {
                $builder->orderBy(\request()->get('sort_by'), 'ASC');
            })
            ->when(\request()->get('createdAtStart'), function (Builder $builder) {
                $builder->whereBetween('created_at', [\request()->get('createdAtStart'), \request()->get('createdAtEnd')]);
            });
    }

    public function index(): JsonResponse
    {
        $brands = $this->filter();
        $redirect_page = \request()->get('page') == "" ? "1" : \request()->get('page');

        if (\request()->get('per_page') != null) {
            $pagination_number = \request()->get('per_page') == "all" ? $brands->count() : \request()->get('per_page');
            $brands = $brands->paginate($pagination_number, ['*'], 'page', $redirect_page);
        } else {
            $brands = $brands->paginate(10, ['*'], 'page', $redirect_page);
        }

        $data = [];
        $data['brands'] = $brands;
        $data['maxId'] = $this->model->query()->max('top');
        return $this->responseWithSuccess('Brands list view', $data);
    }



    public function store($request)
    {
        $commission = $this->model->where('name', $request->name)->first();
        if ($commission) {
            return $this->responseWithError(_trans('Data already exists'), 'name', 422);
        }
        try {
            $commission = new $this->model;
            $commission->name = $request->name;
            $commission->company_id = auth()->user()->company->id;
            $commission->save();
            return $this->responseWithSuccess(_trans('message.Advance type created successfully.'), $commission);
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
                if (hasPermission('advance_type_edit')) {
                    $action_button .= '<a href="' . route('hrm.payroll_advance_type.edit', $data->id) . '" class="dropdown-item"> ' . _trans('common.Edit') . '</a>';
                }
                if (hasPermission('advance_type_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/payroll/advance-type/delete/`)', 'delete');
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
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->rawColumns(array('name', 'status', 'action'))
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
            $commission->status_id = $request->status;
            $commission->save();
            return $this->responseWithSuccess(_trans('message.Advance type update successfully.'), $commission);
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
            return $this->responseWithSuccess(_trans('message.Advance type delete successfully.'), $commission);
        } catch (\Throwable $th) {
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    function getItemList($where = [])
    {
        return  $this->model->query()->where($where)->get();
    }


    //new functions
    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Name'),
            _trans('common.Status'),
            _trans('common.Action'),
        ];
    }

    function table($request)
    {

        
        $data = $this->model->query()->where('company_id', auth()->user()->company_id);
        if ($request->from && $request->to) {
            $data = $data->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if ($request->search) {
            $data = $data->where('name', 'like', '%' . $request->search . '%');
        }
        $data = $data->orderBy('id', 'DESC')->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('advance_type_edit')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('hrm.payroll_advance_type.edit', $data->id) . '`)', 'modal');
                }
                if (hasPermission('advance_type_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/payroll/advance-type/delete/`)', 'delete');
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


    // statusUpdate
    public function statusUpdate($request)
    {
        try {
            
            if (@$request->action == 'active') {
                $advance_type = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Advance type activate successfully.'), $advance_type);
            }
            if (@$request->action == 'inactive') {
                $advance_type = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Advance type inactivate successfully.'), $advance_type);
            }
            return $this->responseWithError(_trans('message.Advance type failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $advance_type = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                return $this->responseWithSuccess(_trans('message.Advance type Delete successfully.'), $advance_type);
            } else {
                return $this->responseWithError(_trans('message.Advance type not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

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
    function editAttributes($department)
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
