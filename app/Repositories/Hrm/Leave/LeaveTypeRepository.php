<?php

namespace App\Repositories\Hrm\Leave;

use App\Models\Hrm\Leave\LeaveType;
use App\Helpers\CoreApp\Traits\AuthorInfoTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class LeaveTypeRepository
{
    use AuthorInfoTrait, RelationshipTrait, ApiReturnFormatTrait;

    protected LeaveType $leaveType;

    public function __construct(LeaveType $leaveType)
    {
        $this->leaveType = $leaveType;
    }

    public function getAll()
    {
        return $this->leaveType->query()->where('company_id', $this->companyInformation()->id)->where('status_id', 1)->get();
    }

    public function index()
    {
    }

    public function dataTable($request, $id = null)
    {
        $leaveType = $this->leaveType->query()->where('company_id', $this->companyInformation()->id);
        if (@$request->from && @$request->to) {
            $leaveType = $leaveType->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if (@$id) {
            $leaveType = $leaveType->where('id', $id);
        }

        return datatables()->of($leaveType->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                $edit = _trans('common.Edit');
                $delete = _trans('common.Delete');
                if (hasPermission('leave_type_update')) {
                    $action_button .= '<a href="' . route('leave.edit', $data->id) . '" class="dropdown-item"> ' . $edit . '</a>';
                }
                if (hasPermission('leave_type_delete')) {
                    $action_button .= actionButton($delete, '__globalDelete(' . $data->id . ',`hrm/leave/delete/`)', 'delete');
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

    public function store($request)
    {
        return $this->leaveType->create($request->all());
    }

    public function show($id)
    {
        return $this->leaveType->find($id);
    }

    public function update($request)
    {
        $this->leaveType->where('id', $request->type_id)->update([
            'name' => $request->name,
            'status_id' => $request->status_id,
        ]);
        return true;
    }

    public function destroy($id)
    {
        $table_name = $this->leaveType->getTable();
        $foreign_id = \Illuminate\Support\Str::singular($table_name) . '_id';
        return \App\Services\Hrm\DeleteService::deleteData($table_name, $foreign_id, $id);
    }


    // new functions 

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Name'),
            _trans('common.Status'),
            _trans('common.Action')

        ];
    }

    function table($request)
    {
        $data =  $this->leaveType->query()->with('status')
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
                if (hasPermission('leave_type_update')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('leave.edit_modal', $data->id) . '`)', 'modal');
                }
                if (hasPermission('leave_type_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/leave/delete/`)', 'delete');
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
                $leave_type = $this->leaveType->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Leave Type activate successfully.'), $leave_type);
            }
            if (@$request->action == 'inactive') {
                $leave_type = $this->leaveType->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Leave Type inactivate successfully.'), $leave_type);
            }
            return $this->responseWithError(_trans('message.Leave Type inactivate failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $leave_type = $this->leaveType->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['deleted_at' => now()]);
                return $this->responseWithSuccess(_trans('message.Leave type delete successfully.'), $leave_type);
            } else {
                return $this->responseWithError(_trans('message.Leave type not found'), [], 400);
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
    function editAttributes($leaveType)
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
                'value' => @$leaveType->name,
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
                        'active' => $leaveType->status_id == 1 ? true : false,
                    ],
                    [
                        'text' => _trans('payroll.Inactive'),
                        'value'  => 4,
                        'active' =>  $leaveType->status_id == 4 ? true : false,
                    ]
                ]
            ]

        ];
    }


    function newStore($request)
    {
        try {
            if ($this->isExistsWhenStore($this->leaveType, 'name', $request->name)) {
                $leaveType = new $this->leaveType;
                $leaveType->name = $request->name;
                $leaveType->status_id = $request->status;
                $leaveType->company_id = auth()->user()->company_id;
                $leaveType->save();
                return $this->responseWithSuccess(_trans('message.Leave type store successfully.'), 200);
            } else {
                return $this->responseWithError(_trans('message.Data already exists'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    function newUpdate($request, $id)
    {
        try {
            $leaveTypeModel = $this->leaveType->where('company_id', auth()->user()->company_id)->find($id);
            if ($this->isExistsWhenUpdate($leaveTypeModel, $this->leaveType, 'name', $request->name)) {
                $this->leaveType->where('id', $id)->update([
                    'name' => $request->name,
                    'status_id' => $request->status,
                ]);
                return $this->responseWithSuccess(_trans('message.Leave type update successfully.'), 200);
            } else {
                return $this->responseWithError(_trans('message.Data already exists'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
