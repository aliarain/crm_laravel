<?php

namespace App\Repositories\Hrm\Designation;

use function route;
use function datatables;
use function actionButton;
use function start_end_datetime;
use App\Models\Traits\RelationCheck;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Hrm\Designation\Designation;
use App\Helpers\CoreApp\Traits\AuthorInfoTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class DesignationRepository
{

    use AuthorInfoTrait,
        RelationshipTrait,
        RelationCheck,
        ApiReturnFormatTrait;

    protected Designation $designation;

    public function __construct(Designation $designation)
    {
        $this->designation = $designation;
    }

    public function getAll()
    {
        return $this->designation::query()->where('company_id', $this->companyInformation()->id)->get();
    }

    public function getActiveAll()
    {
        return $this->designation::query()->where('status_id', 1)->where('company_id', $this->companyInformation()->id)->get();
    }

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Title'),
            _trans('common.Status'),
            _trans('common.Action')
        ];
    }

    public function index()
    {
    }

    public function store($request)
    {
        $company = $this->designation->query()->create($request->all());
        $this->createdBy($company);
        return true;
    }

    function table($request)
    {
        
        $data = $this->designation->query()->with('status')
            ->where('company_id', auth()->user()->company_id);
        $where = array();

        if ($request->search) {
            $where[] = ['title', 'like', '%' . $request->search . '%'];
        }
        $data = $data
            ->where($where)
            ->orderBy('id', 'desc')
            ->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('designation_update')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('designation.edit_modal', $data->id) . '`)', 'modal');
                }
                if (hasPermission('designation_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/designation/delete/`)', 'delete');
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
                    'title' => $data->title,
                    'status' => '<small class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</small>',
                    'action' => $button
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

    public function dataTable($request, $id = null)
    {
        $designation = $this->designation->query()->with('status')->where('company_id', $this->companyInformation()->id);
        if (@$request->from && @$request->to) {
            $designation = $designation->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if (@$id) {
            $designation = $designation->where('id', $id);
        }

        return datatables()->of($designation->orderBy('id', 'DESC')->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                $edit = _trans('common.Edit');
                $delete = _trans('common.Delete');
                if (hasPermission('designation_update')) {
                    $action_button .= '<a href="' . route('designation.edit', $data->id) . '" class="dropdown-item"> ' . $edit . '</a>';
                }
                if (hasPermission('designation_delete')) {
                    $action_button .= actionButton($delete, '__globalDelete(' . $data->id . ',`hrm/designation/delete/`)', 'delete');
                }
                $button = getActionButtons($action_button);
                return $button;
            })
            ->addColumn('title', function ($data) {
                return @$data->title;
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->rawColumns(array('title', 'status', 'action'))
            ->make(true);
    }

    public function show($id)
    {
    }

    public function update($request): bool
    {
        $designation = $this->designation->where('id', $request->designation_id)->first();
        $designation->title = $request->title;
        $designation->status_id = $request->status_id;
        $designation->save();
        $this->updatedBy($designation);
        return true;
    }

    public function destroy($designation)
    {
        $table_name = $this->designation->getTable();
        $foreign_id = \Illuminate\Support\Str::singular($table_name) . '_id';
        return \App\Services\Hrm\DeleteService::deleteData($table_name, $foreign_id, $designation->id);
    }

    // statusUpdate
    public function statusUpdate($request)
    {
        try {
            
            if (@$request->action == 'active') {
                $designation = $this->designation->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Designation activate successfully.'), $designation);
            }
            if (@$request->action == 'inactive') {
                $designation = $this->designation->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Designation inactivate successfully.'), $designation);
            }
            return $this->responseWithError(_trans('message.Designation failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $designation = $this->designation->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['deleted_at' => now()]);
                return $this->responseWithSuccess(_trans('message.Designation delete successfully.'), $designation);
            } else {
                return $this->responseWithError(_trans('message.Designation not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function createAttributes()
    {
        return [
            'title' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id' => 'title',
                'class' => 'form-control ot-form-control ot_input',
                'col' => 'col-md-12 form-group',
                'label' => _trans('common.Title')
            ],
            'status' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id' => 'status',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3 mt-3',
                'label' => _trans('common.Status'),
                'options' => [
                    [
                        'text' => _trans('payroll.Active'),
                        'value' => 1,
                        'active' => true,
                    ],
                    [
                        'text' => _trans('payroll.Inactive'),
                        'value' => 4,
                        'active' => false,
                    ]
                ]
            ]
        ];
    }

    function editAttributes($designation)
    {
        return [
            'title' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id' => 'title',
                'class' => 'form-control ot-form-control ot_input',
                'col' => 'col-md-12 form-group',
                'label' => _trans('common.Title'),
                'value' => @$designation->title,
            ],
            'status' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id' => 'status',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3 mt-3',
                'label' => _trans('common.Status'),
                'options' => [
                    [
                        'text' => _trans('payroll.Active'),
                        'value' => 1,
                        'active' => $designation->status_id == 1 ? true : false,
                    ],
                    [
                        'text' => _trans('payroll.Inactive'),
                        'value' => 4,
                        'active' => $designation->status_id == 4 ? true : false,
                    ]
                ]
            ]
        ];
    }

    //new functions

    function newStore($request)
    {
        try {
            if ($this->isExistsWhenStore($this->designation, 'title', $request->title)) {
                $designation = new $this->designation;
                $designation->title = $request->title;
                $designation->status_id = $request->status;
                $designation->company_id = auth()->user()->company_id;
                $designation->save();
                $this->createdBy($designation);
                return $this->responseWithSuccess(_trans('message.Designation store successfully.'), 200);
            } else {
                return $this->responseWithError(_trans('message.Data already exists'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function newUpdate($request, $designationModel)
    {
        try {
            $designationModel = $this->designation->where('company_id', auth()->user()->company_id)->where('id', $designationModel)->first();

            if ($this->isExistsWhenUpdate($designationModel, $this->designation, 'title', $request->title)) {
                $designation = $designationModel;
                $designation->title = $request->title;
                $designation->status_id = $request->status;
                $designation->company_id = auth()->user()->company_id;
                $designation->save();
                $this->updatedBy($designation);
                return $this->responseWithSuccess(_trans('message.Designation update successfully.'), 200);
            } else {
                return $this->responseWithError(_trans('message.Data already exists'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
