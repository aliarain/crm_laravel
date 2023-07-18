<?php

namespace App\Repositories\Hrm\Leave;

use Illuminate\Support\Facades\Log;
use App\Models\Hrm\Leave\AssignLeave;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use Illuminate\Support\Facades\DB;

class AssignLeaveRepository
{
    use RelationshipTrait, ApiReturnFormatTrait;

    protected AssignLeave $assignLeave;

    public function __construct(AssignLeave $assignLeave)
    {
        $this->assignLeave = $assignLeave;
    }

    public function index()
    {
    }

    public function dataTable($request, $id = null)
    {
        $assignLeave = $this->assignLeave->query()->where('company_id', $this->companyInformation()->id);
        if (@$request->department_id) {
            $assignLeave = $assignLeave->where('department_id', $request->department_id);
        }
        if (@$id) {
            $assignLeave = $assignLeave->where('id', $id);
        }

        return datatables()->of($assignLeave->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                $edit = _trans('common.Edit');
                $delete = _trans('common.Delete');
                if (hasPermission('leave_assign_update')) {
                    $action_button .= '<a href="' . route('assignLeave.edit', $data->id) . '" class="dropdown-item"> ' . $edit . '</a>';
                }
                if (hasPermission('leave_assign_delete')) {
                    $action_button .= actionButton($delete, '__globalDelete(' . $data->id . ',`hrm/leave/assign/delete/`)', 'delete');
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
            ->addColumn('days', function ($data) {
                return @$data->days;
            })
            ->addColumn('department', function ($data) {
                return @$data->department->title;
            })
            ->addColumn('type', function ($data) {
                return @$data->type->name;
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->rawColumns(array('days', 'department', 'type', 'status', 'action'))
            ->make(true);
    }

    public function store($request)
    {

        try {
            if ($this->isExistsWhenStoreMultipleColumn($this->assignLeave, 'department_id', 'type_id', $request->type_id, $request->department_id)) {
                $assign_leave = new $this->assignLeave;
                $assign_leave->days = $request->days;
                $assign_leave->type_id = $request->type_id;
                $assign_leave->department_id = $request->department_id;
                $assign_leave->company_id = auth()->user()->company_id;
                $assign_leave->status_id = $request->status_id;
                $assign_leave->save();
                return $this->responseWithSuccess(_trans('message.Assign leave store successfully.'), 200);
            } else {
                return $this->responseWithError(_trans('message.Data already exists'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function show($id): object
    {
        return $this->assignLeave->query()->find($id);
    }

    public function pre_update($request, $id)
    {


        $assignLeaveModel = $this->assignLeave->where('company_id', auth()->user()->company_id)->where('id', $id)->first();
        // $assign_leave = $assignLeaveModel;
        if (!empty($assignLeaveModel)) {
            $assignLeaveModel->days = $request->days;
            $assignLeaveModel->type_id = $request->type_id;
            $assignLeaveModel->department_id = $request->department_id;
            $assignLeaveModel->status_id = $request->status_id;
            $assignLeaveModel->save();
            return $this->responseWithSuccess(_trans('message.Assign leave update successfully.'), 200);
        }
        return $this->responseWithSuccess(_trans('message.Assign leave does not update successfully.'), 400);
    }
    public function update($request, $id)
    {

        try {
            $assignLeaveModel = $this->assignLeave->where('company_id', auth()->user()->company_id)->where('id', $id)->first();
            if (!empty($assignLeaveModel)) {
                if ($this->isExistsWhenUpdate($assignLeaveModel, $this->assignLeave, 'department_id', $request->department_id)) {
                    $assign_leave = $assignLeaveModel;
                    $assign_leave->days = $request->days;
                    $assign_leave->type_id = $request->type_id;
                    $assign_leave->department_id = $request->department_id;
                    $assign_leave->status_id = $request->status_id;
                    $assign_leave->save();
                    return $this->responseWithSuccess(_trans('message.Assign leave update successfully.'), 200);
                } else {
                    return $this->responseWithError(_trans('message.Data already exists'), [], 400);
                }
            }else{
                return $this->responseWithError(_trans('message.Company not found'),[], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function destroy($id)
    {
        $table_name = $this->assignLeave->getTable();
        $foreign_id = \Illuminate\Support\Str::singular($table_name) . '_id';
        return \App\Services\Hrm\DeleteService::deleteData($table_name, $foreign_id, $id);
    }



    // new functions 

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Department'),
            _trans('common.Type'),
            _trans('common.Days'),
            _trans('common.Status'),
            _trans('common.Action')

        ];
    }

    function table($request)
    {
        
        $data =  $this->assignLeave->query()->where('company_id', auth()->user()->company_id);
        if (@$request->department_id) {
            $data = $data->where('department_id', $request->department_id);
        }
        if ($request->search) {
            $data = $data->whereHas('department', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }
        $data = $data->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('leave_assign_update')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('assignLeave.edit', $data->id) . '`)', 'modal');
                }
                if (hasPermission('leave_assign_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/leave/assign/delete/`)', 'delete');
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
                    'days'       => $data->days,
                    'department' => @$data->department->title,
                    'type'       => @$data->type->name,
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
                $assign_leave = $this->assignLeave->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Leave Assign activate successfully.'), $assign_leave);
            }
            if (@$request->action == 'inactive') {
                $assign_leave = $this->assignLeave->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Leave Assign inactivate successfully.'), $assign_leave);
            }
            return $this->responseWithError(_trans('message.Leave Assign inactivate failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $assign_leave = $this->assignLeave->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['deleted_at' => now()]);
                return $this->responseWithSuccess(_trans('message.Assign leave delete successfully.'), $assign_leave);
            } else {
                return $this->responseWithError(_trans('message.Assign leave not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function createAttributes()
    {
        return [
            'days' => [
                'field' => 'input',
                'type' => 'number',
                'required' => true,
                'id'    => 'days',
                'class' => 'form-control ot_input ot-form-control',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Days')
            ],
            'department_id' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'department_id',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3 ',
                'label' => _trans('common.Department'),
                'options' => DB::table('departments')->where('company_id', auth()->user()->company_id)->where('status_id', 1)->get()->map(function ($data) {
                    return [
                        'text' => $data->title,
                        'value' => $data->id,
                        'active' => false
                    ];
                })->toArray()
            ],
            'type_id' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'type_id',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3 ',
                'label' => _trans('leave.Leave Type'),
                'options' => DB::table('leave_types')->where('company_id', auth()->user()->company_id)->where('status_id', 1)->get()->map(function ($data) {
                    return [
                        'text' => $data->name,
                        'value' => $data->id,
                        'active' => false
                    ];
                })->toArray()
            ],
            'status_id' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'status_id',
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
    function editAttributes($updateModel)
    {
        return [
            'days' => [
                'field' => 'input',
                'type' => 'number',
                'required' => true,
                'id'    => 'days',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'value' => @$updateModel->days,
                'label' => _trans('common.Days')
            ],
            'department_id' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'department_id',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3 ',
                'label' => _trans('common.Department'),
                'options' => DB::table('departments')->where('company_id', auth()->user()->company_id)->where('status_id', 1)->get()->map(function ($data) use ($updateModel) {
                    return [
                        'text' => $data->title,
                        'value' => $data->id,
                        'active' =>  $updateModel->department_id == 1 ? true : false,
                    ];
                })->toArray()
            ],
            'type_id' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'type_id',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('leave.Leave Type'),
                'options' => DB::table('leave_types')->where('company_id', auth()->user()->company_id)->where('status_id', 1)->get()->map(function ($data) use ($updateModel) {
                    return [
                        'text' => $data->name,
                        'value' => $data->id,
                        'active' =>  $updateModel->type_id == 1 ? true : false,
                    ];
                })->toArray()
            ],
            'status_id' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'status_id',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Status'),
                'options' => [
                    [
                        'text' => _trans('payroll.Active'),
                        'value'  => 1,
                        'active' => $updateModel->status_id == 1 ? true : false,
                    ],
                    [
                        'text' => _trans('payroll.Inactive'),
                        'value'  => 4,
                        'active' => $updateModel->status_id == 4 ? true : false,
                    ]
                ]
            ]

        ];
    }
}
