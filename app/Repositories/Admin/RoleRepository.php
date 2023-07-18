<?php

namespace App\Repositories\Admin;

use App\Models\User;
use App\Models\Role\Role;
use Illuminate\Support\Str;
use App\Services\Hrm\DeleteService;
use App\Models\Traits\RelationCheck;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Permission\Permission;
use Illuminate\Support\Facades\Cache;
use App\Helpers\CoreApp\Traits\AuthorInfoTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class RoleRepository
{
    use AuthorInfoTrait, RelationshipTrait, RelationCheck, ApiReturnFormatTrait;

    protected $role;
    protected $users;

    public function __construct(Role $role, User $users)
    {
        $this->role = $role;
        $this->users = $users;
    }

    public function get($id)
    {
        return $this->role->query()->where('company_id', $this->companyInformation()->id)->findOrFail($id);
    }

    public function getAll()
    {
        return $this->role->query()->where('company_id', $this->companyInformation()->id)->where('status_id', 1)->where('id', '!=', '1')->where('permissions', '!=', '')->get();
       
    }

    public function getPermission()
    {
        return Permission::get();
    }

    public function index()
    {
        // TODO: Implement index() method.
    }

    public function dataTable($request, $id = null)
    {
        $roles = $this->role->query()->where('company_id', $this->companyInformation()->id)->with('status')->where('id', '!=', '1');
        if (@$request->from && @$request->to) {
            $roles = $roles->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if (@$id) {
            $roles = $roles->where('id', $id);
        }

        return datatables()->of($roles->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                $edit = _trans('common.Edit');
                $delete = _trans('common.Delete');
                if (hasPermission('role_update')) {
                    $action_button .= '<a href="' . route('roles.edit', $data->id) . '" class="dropdown-item"> ' . $edit . '</a>';
                }
                if (hasPermission('role_delete')) {

                    $action_button .= actionButton($delete, '__globalDelete(' . $data->id . ',`hrm/roles/delete/`)', 'delete');
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
            ->addColumn('permissions', function ($data) {
                return $data->permissions != null ? count($data->permissions) : 0;
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->rawColumns(array('name', 'status', 'action'))
            ->make(true);
    }

    public function store($request)
    {
        $request['slug'] = Str::slug($request->name, '-');
        $this->role->query()->create($request->all());
    }

    public function show($id)
    {
        return $this->role->query()->find($id);
    }

    public function update($request)
    {
        try {

            if($request->apply_for_all == 1){
                $users = $this->users->where('role_id', $request->role_id )->get();
    
                foreach($users as $user){
                    $this->users->where('id',$user->id)->update(['permissions'=>json_encode($request->permissions)]);
                }
            }
    
            $this->role->query()->where('id', $request->role_id)->update($request->only(['name', 'status_id', 'permissions']));
            return true;

        } catch (\Throwable $th) {

            return $this->responseWithError($th->getMessage(), [], 400);

        }
    }

    public function destroy($id)
    {
        $foreign_id = \Illuminate\Support\Str::singular($this->role->getTable()) . '_id';
        return \App\Services\Hrm\DeleteService::deleteData($this->role->getTable(), $foreign_id, $id);
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


    // data table functions
    function table($request)
    { {
            $data =  $this->role->query()->with('status')
                ->where('company_id', auth()->user()->company_id);
            $where = array();
            if ($request->search) {
                $where[] = ['name', 'like', '%' . $request->search . '%'];
            }
            $data = $data
                ->where($where)
                ->orderBy('id', 'DESC')
                ->paginate($request->limit ?? 2);
            return [
                'data' => $data->map(function ($data) {
                    $action_button = '';
                    if (hasPermission('role_update')) {
                        $action_button .= '<a href="' . route('roles.edit', $data->id) . '" class="dropdown-item"> <span class="icon mr-8"><i class="fa-solid fa-pen-to-square"></i></span>' . _trans('common.Edit') . '</a>';
                    }
                    if (hasPermission('role_delete')) {
                        $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/roles/delete/`)', 'delete');
                    }
                    $system_roles=['admin','manager','staff'];
                    if(in_array($data->slug,$system_roles)){
                        $button='<small class="badge badge-danger">' . _trans('common.Restricted') . '</small>';
                        
                    }else{

                        $button = ' <div class="dropdown dropdown-action">
                                        <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                        ' . $action_button . '
                                        </ul>
                                    </div>';
                    }

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
    }

    // statusUpdate
    public function statusUpdate($request)
    {
        try {
            
            if (@$request->action == 'active') {
                $role = $this->role->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Role activate successfully.'), $role);
            }
            if (@$request->action == 'inactive') {
                $role = $this->role->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Role inactivate successfully.'), $role);
            }
            return $this->responseWithError(_trans('message.Role inactivate failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $role = $this->role->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['deleted_at' => now()]);
                return $this->responseWithSuccess(_trans('message.Role activate successfully.'), $role);
            } else {
                return $this->responseWithError(_trans('message.Role not found'), [], 400);
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


    function newStore($request)
    {
        try {
            if ($this->isExistsWhenStore($this->role, 'name', $request->name)) {
                $role = new $this->role;
                $role->name = $request->name;
                $role->slug = Str::slug($request->name, '-');
                $role->status_id = $request->status;
                $role->company_id = auth()->user()->company_id;
                $role->save();
                $this->createdBy($role);
                return $this->responseWithSuccess(_trans('message.Role delete successfully.'), 200);
            } else {
                return $this->responseWithError(_trans('message.Data already exists'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
