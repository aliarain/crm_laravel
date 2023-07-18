<?php

namespace App\Repositories\Hrm\Department;

use Illuminate\Support\Facades\Log;
use App\Models\Traits\RelationCheck;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Cache;
use App\Models\Hrm\Department\Department;
use App\Helpers\CoreApp\Traits\AuthorInfoTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class DepartmentRepository
{

    use AuthorInfoTrait, RelationshipTrait, RelationCheck, ApiReturnFormatTrait;

    protected Department $department;
    protected $model;

    public function __construct(Department $department, Department $model)
    {
        $this->department = $department;
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->query()->where('company_id', $this->companyInformation()->id)->get();
    }
    public function getActiveAll()
    {
        return $this->model->query()->where('status_id',1)->where('company_id', $this->companyInformation()->id)->get();
    }

    public function index()
    {
    }

    public function store($request): bool
    {
        $this->department->query()->create($request->all());
        return true;
    }

    public function dataTable($request, $id = null)
    {
        $department = $this->department->query()->with('status', 'users')->where('company_id', $this->companyInformation()->id);
        if (@$request->from && @$request->from != NULL && @$request->to &&  !is_null($request->to)) {
            $department = $department->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if (@$id) {
            $department = $department->where('id', $id);
        }

        return datatables()->of($department->latest()->get())
            ->addColumn('action', function ($data) {

                $action_button = '';
                $edit = _trans('common.Edit');
                $delete = _trans('common.Delete');
                if (hasPermission('department_update')) {
                    $action_button .= '<a href="' . route('department.edit', $data->id) . '" class="dropdown-item"> ' . $edit . '</a>';
                }
                if (hasPermission('department_delete')) {
                    $action_button .= actionButton($delete, '__globalDelete(' . $data->id . ',`hrm/department/delete/`)', 'delete');
                }
                $button = getActionButtons($action_button);
                return $button;
            })
            ->addColumn('title', function ($data) {
                return @$data->title;
            })
            ->addColumn('employees', function ($data) {
                $str = '';
                $left_count = 0;

                foreach ($data->users->take(3) as $user) {
                    $str .= '<img src="' . uploaded_asset($user->avatar_id) . '" width="50px" height="50px" class="img-circle __img-border" alt="User Image">';
                }
                if ($data->users->count() > 3) {
                    $left_count = $data->users->count() - 3;
                    $str .= '<br>';
                }
                if ($left_count > 0) {
                    $str .= '<span class=" __employee_count">+' . $left_count . ' more peoples</span>';
                }
                return $str;
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->rawColumns(array('title', 'employees', 'status', 'action'))
            ->make(true);
    }

    public function show($department)
    {
        return $department;
    }

    public function update($request): bool
    {
        $department = $this->department->query()->where('id', $request->department_id)->first();
        $department->title = $request->title;
        $department->status_id = $request->status_id;
        $department->save();
        return true;
    }

    public function destroy($department)
    {

        $table_name = $this->department->getTable();
        $foreign_id = \Illuminate\Support\Str::singular($table_name) . '_id';
        return \App\Services\Hrm\DeleteService::deleteData($table_name, $foreign_id, $department->id);
    }


    // new functions

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Title'),
            _trans('common.Status'),
            _trans('common.Action')

        ];
    }

    function table($request)
    { 
            
            $data =  $this->department->query()->with('status')
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
                    if (hasPermission('department_update')) {
                        $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('department.edit_modal', $data->id) . '`)', 'modal');
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
                        'id' => $data->id,
                        'title' => $data->title,
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
                $department = $this->department->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Department activate successfully.'), $department);
            }
            if (@$request->action == 'inactive') {
                $department = $this->department->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Department inactivate successfully.'), $department);
            }
            return $this->responseWithError(_trans('message.Department failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $department = $this->department->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['deleted_at' => now()]);
                return $this->responseWithSuccess(_trans('message.Department Delete successfully.'), $department);
            } else {
                return $this->responseWithError(_trans('message.Department not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    //new functions

    function createAttributes()
    {
        return [
            'title' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'title',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Title')
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
            'title' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'title',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Title'),
                'value' => @$department->title,
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


    function newStore($request)
    {
        try {
            if ($this->isExistsWhenStore($this->department, 'title', $request->title)) {
                $department = new $this->department;
                $department->title = $request->title;
                $department->status_id = $request->status;
                $department->company_id = auth()->user()->company_id;
                $department->save();
                $this->createdBy($department);
                return $this->responseWithSuccess(_trans('message.Department store successfully.'), 200);
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
            $departmentModel = $this->department->where('company_id', auth()->user()->company_id)->find($id);
            if ($this->isExistsWhenUpdate($departmentModel,$this->department, 'title', $request->title)) {
                $department = $departmentModel;
                $department->title = $request->title;
                $department->status_id = $request->status;
                $department->company_id = auth()->user()->company_id;
                $department->save();
                $this->updatedBy($department);
                return $this->responseWithSuccess(_trans('message.Department update successfully.'), 200);
            } else {
                return $this->responseWithError(_trans('message.Data already exists'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function getDepartmentStaff(){
        $department = $this->department->where('company_id', auth()->user()->company_id)->get();
        $departmentStaff = [];
        foreach ($department as $key => $value) {
            $departmentStaff[$key]['name'] = $value->title;
            $departmentStaff[$key]['value'] = $value->users()->count();
        }
        return $departmentStaff;
    }
}
