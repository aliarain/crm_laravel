<?php

namespace  App\Repositories\Hrm\Payroll;

use Validator;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Models\Payroll\SalarySetup;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Payroll\SalarySetupDetails;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use Illuminate\Support\Facades\Log;

class PayrollSetUpRepository
{
    use ApiReturnFormatTrait;
    protected $model;

    public function __construct(User $model)
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

    public function salarySetup($filter = null)
    {
        $model = SalarySetupDetails::query();
        if ($filter) {
            $model = $model->where($filter)->first();
        }
        return $model;
    }


    public function datatable($company_id, $request)
    {
        $params = [];
        $users = $this->model->query()->with('department', 'designation', 'role', 'shift', 'status')
            ->where('company_id', $company_id)
            ->select('id', 'company_id', 'role_id', 'department_id', 'designation_id', 'name', 'employee_id', 'basic_salary', 'shift_id', 'status_id', 'is_hr');

        if (@$request->user_id) {
            $params['id'] = $request->user_id;
        }
        if (@$request->department_id) {
            $params['department_id'] = $request->department_id;
        }
        $users->where($params);
        return datatables()->of($users->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                if (hasPermission('view_payroll_set')) {
                    $action_button .= actionButton('Setup', route('hrm.payroll_setup.user_setup', [$data->id, 'contract']), 'Set');
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
            ->addColumn('employee', function ($data) {
                return @$data->name;
            })
            ->addColumn('employee_id', function ($data) {
                $id = @$data->employee_id ? @$data->employee_id : '0000';
                if (hasPermission('view_payroll_set')) {
                    return '<a class="text-success text-decoration-none text-muted" href="' . route('hrm.payroll_setup.user_setup', [$data->id, 'contract']) . '" class="dropdown-item"> #' . $id . '</a>';
                } else {
                    return '<span" class="text-success text-decoration-none text-muted"> #' . $id . '</span>';
                }
            })
            ->addColumn('department', function ($data) {
                return @$data->department->title;
            })
            ->addColumn('designation', function ($data) {
                return @$data->designation->title;
            })
            ->addColumn('role', function ($data) {
                return @$data->role->name;
            })
            ->addColumn('shift', function ($data) {
                return @$data->shift->name;
            })
            ->addColumn('basic_salary', function ($data) {
                return @$data->basic_salary;
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->rawColumns(array('employee', 'employee_id', 'basic_salary', 'department', 'designation', 'role', 'shift', 'status', 'action'))
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
            $commission->save();
            return $this->responseWithSuccess(_trans('message.Item Update successfully.'), $commission);
        } catch (\Throwable $th) {
            return $this->responseExceptionError($th->getMessage(), [], 400);
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
            return $this->responseWithSuccess(_trans('message.Item Delete successfully.'), $commission);
        } catch (\Throwable $th) {
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    function getSalaryInfo($id, $company_id)
    {
        $commission = $this->model(['id' => $id, 'company_id' => $company_id]);
        if (!$commission) {
            return $this->responseWithError(_trans('Data not found'), 'id', 404);
        }
        return $commission;
    }

    public function store($request)
    {
        try {

            $user = $this->model->find($request->user_id);
            if (!$user) {
                return $this->responseWithError(_trans('Data not found'), 'id', 404);
            }
            $salary_set_up  = $user->salary_setup;
            if (!$salary_set_up) {
                $salary_set_up                = new SalarySetup;
                $salary_set_up->company_id    =  $user->company_id;
                $salary_set_up->user_id       =  $user->id;
                $salary_set_up->gross_salary  =  $user->basic_salary;
                $salary_set_up->created_by    =   auth()->user()->id;
                $salary_set_up->updated_by    =   auth()->user()->id;
                $salary_set_up->save();
            }
            $set_up_detail = $salary_set_up->salarySetupDetails->where('commission_id', $request->set_up_id)->first();
            if (!$set_up_detail) {
                $set_up_detail                  =  new SalarySetupDetails;
                $set_up_detail->company_id      =  $salary_set_up->company_id;
                $set_up_detail->user_id         =  $user->id;
                $set_up_detail->salary_setup_id =  $salary_set_up->id;
                $set_up_detail->commission_id   =  $request->set_up_id;
                $set_up_detail->amount          =  $request->amount;
                $set_up_detail->amount_type     =  $request->type;
                $set_up_detail->status_id       =  $request->status_id;
                $set_up_detail->created_by      =  auth()->user()->id;
                $set_up_detail->updated_by      =  auth()->user()->id;
                $set_up_detail->save();
            } else {
                return $this->responseWithError(_trans('Data already exists'), 'id', 404);
            }
            return $this->responseWithSuccess(_trans('message.Item created successfully.'), $salary_set_up);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    public function salary_details_update($request, $id)
    {
        try {

            $user = $this->model->find($request->user_id);
            if (!$user) {
                return $this->responseWithError(_trans('Data not found'), 'id', 404);
            }
            $salary_set_up  = $user->salary_setup;
            $set_up_detail = $salary_set_up->salarySetupDetails->where('id', $id)->first();
            $old = $salary_set_up->salarySetupDetails->where('commission_id', $request->set_up_id)->first();
            if (!blank(@$old) && @$set_up_detail->id != @$old->id) {
                return $this->responseWithError(_trans('Already added'), 'id', 404);
            }
            $set_up_detail->commission_id   =  $request->set_up_id;
            $set_up_detail->amount          =  $request->amount;
            $set_up_detail->amount_type     =  $request->type;
            $set_up_detail->status_id       =  $request->status_id;
            $set_up_detail->updated_by      =  auth()->user()->id;
            $set_up_detail->save();

            return $this->responseWithSuccess(_trans('message.Item created successfully.'), $salary_set_up);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    // new functions

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Employee'),
            _trans('common.Designation'),
            _trans('common.Department'),
            _trans('common.Shift'),
            _trans('common.Basic Salary'),
            _trans('common.Status'),
            _trans('common.Action')
        ];
    }

    function table($request)
    {
        $params = [];
        if (@$request->user_id) {
            $params['id'] = $request->user_id;
        }
        if (@$request->department) {
            $params['department_id'] = $request->department;
        }
        $data = $this->model->query()->with('department', 'designation', 'role', 'shift', 'status')
            ->where('company_id', auth()->user()->company_id)
            ->where($params)
            ->select('id', 'company_id', 'role_id', 'department_id', 'designation_id', 'name', 'employee_id', 'basic_salary', 'shift_id', 'status_id', 'is_hr');

        if ($request->search) {
            $data = $data->where('name', 'like', '%' . $request->search . '%')
                 ->orWhere('basic_salary', 'LIKE', '%' . $request->search . '%');
        }
        $data = $data->paginate($request->limit ?? 2);

        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('view_payroll_set')) {
                    $action_button .= actionButton(_trans('common.Set Contract'), route('hrm.payroll_setup.user_setup', [$data->id, 'contract']), 'Set');
                }
                if (hasPermission('view_payroll_set')) {
                    $action_button .= actionButton(_trans('payroll.Set Commission'), route('hrm.payroll_setup.user_commission_setup', $data->id), 'Set');
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
                    'department'       => @$data->department->title,
                    'designation'       => @$data->designation->title,
                    'shift'       => @$data->shift->name,
                    'basic_salary'       => showAmount(@$data->basic_salary),
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

    function setCommissionFields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Name'),
            _trans('common.Type'),
            _trans('common.Amount'),
            _trans('common.Status'),
            _trans('common.Action')
        ];
    }

    function userCommissionTable($request, $user_id)
    {

        if (!is_Admin()) {
            $user_id = auth()->id();
        }
        $data = SalarySetupDetails::where('company_id', auth()->user()->company_id)
            ->where('user_id', $user_id);

        if ($request->search) {
            $data = $data->whereHas('commission', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }
        if (@$request->from && @$request->to) {
            $data = $data->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        $data = $data->orderBy('id', 'desc')->paginate($request->limit ?? 2);

        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('edit_payroll_set')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('hrm.payroll_setup.item_list_edit_modal', $data->id) . '`)', 'modal');
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
                    'name'       => @$data->commission->name,
                    'type'       =>  @$data->commission->type == 1 ? '<span class="badge badge-success">' . _trans('payroll.Addition') . '</span>' : '<span class="badge badge-danger">' . _trans('payroll.Deduction') . '</span>',
                    'amount'     => @$data->amount_type == 1 ?  showAmount(@$data->amount) : @$data->amount . ' %',
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
    function createSetCommissionAttributes($commissionModel)
    {
        return [
            'amount' => [
                'field' => 'input',
                'type' => 'number',
                'required' => true,
                'id'    => 'amount',
                'class' => 'form-control ot-form-control ot_input ',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Amount'),
            ],
            'set_up_id' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'set_up_id',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Commission Type'),
                'options' => $commissionModel->map(function ($data) {
                    return [
                        'text' => $data->name,
                        'value' => $data->id,
                        'active' => false
                    ];
                })->toArray()
            ],
            'type' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'type',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Amount type'),
                'options' => [
                    [
                        'text' => _trans('common.Fixed'),
                        'value'  => 1,
                        'active' =>  false,
                    ],
                    [
                        'text' => _trans('common.Percentage'),
                        'value'  => 2,
                        'active' =>   false,
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
                        'active' =>  false,
                    ],
                    [
                        'text' => _trans('payroll.Inactive'),
                        'value'  => 4,
                        'active' =>   false,
                    ]
                ]
            ]

        ];
    }
    function editSetCommissionAttributes($commissionModel, $list)
    {
        return [
            'user_id' => [
                'field' => 'input',
                'type' => 'number',
                'required' => true,
                'id'    => 'user_id',
                'class' => 'form-control ot-form-control ot_input ',
                'col'   => 'col-md-12 form-group d-none',
                'label' => _trans('common.User'),
                'value' => @$commissionModel->user_id,
            ],
            'amount' => [
                'field' => 'input',
                'type' => 'number',
                'required' => true,
                'id'    => 'amount',
                'class' => 'form-control ot-form-control ot_input ',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Amount'),
                'value' => @$commissionModel->amount,
            ],
            'set_up_id' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'set_up_id',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Commission Type'),
                'options' => $list->map(function ($data) use ($commissionModel) {
                    return [
                        'text' => $data->name,
                        'value' => $data->id,
                        'active' =>  $commissionModel->set_up_id == $data->id ? true : false
                    ];
                })->toArray()
            ],
            'type' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'type',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Amount type'),
                'options' => [
                    [
                        'text' => _trans('common.Fixed'),
                        'value'  => 1,
                        'active' =>  $commissionModel->type == 1 ? true : false,
                    ],
                    [
                        'text' => _trans('common.Percentage'),
                        'value'  => 2,
                        'active' => $commissionModel->type == 2 ? true : false,
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

    function create_user_commission_setup($request, $user_id)
    {
        try {
            

            $user = $this->model->find($user_id);
            if (!$user) {
                return $this->responseWithError(_trans('response.Data not found'), 'id', 400);
            }
            $salary_set_up  = $user->salary_setup;
            if (!$salary_set_up) {
                $salary_set_up                = new SalarySetup;
                $salary_set_up->company_id    =  $user->company_id;
                $salary_set_up->user_id       =  $user->id;
                $salary_set_up->gross_salary  =  $user->basic_salary;
                $salary_set_up->created_by    =   auth()->user()->id;
                $salary_set_up->updated_by    =   auth()->user()->id;
                $salary_set_up->save();
            }
            $set_up_detail = $salary_set_up->salarySetupDetails->where('commission_id', $request->set_up_id)->first();
            if (!$set_up_detail) {
                $set_up_detail                  =  new SalarySetupDetails;
                $set_up_detail->company_id      =  $salary_set_up->company_id;
                $set_up_detail->user_id         =  $user->id;
                $set_up_detail->salary_setup_id =  $salary_set_up->id;
                $set_up_detail->commission_id   =  $request->set_up_id;
                $set_up_detail->amount          =  $request->amount;
                $set_up_detail->amount_type     =  $request->type;
                $set_up_detail->status_id       =  $request->status;
                $set_up_detail->created_by      =  auth()->user()->id;
                $set_up_detail->updated_by      =  auth()->user()->id;
                $set_up_detail->save();
            } else {
                return $this->responseWithError(_trans('message.Data already exists'), 'id', 400);
            }
            return $this->responseWithSuccess(_trans('message.Item created successfully.'), $salary_set_up);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    function update_user_commission_setup($request, $id)
    {
        try {
            

            $user = $this->model->find($request->user_id);
            if (!$user) {
                return $this->responseWithError(_trans('Data not found'), 'id', 400);
            }
            $salary_set_up  = $user->salary_setup;
            $set_up_detail = $salary_set_up->salarySetupDetails->where('id', $id)->first();
            $old = $salary_set_up->salarySetupDetails->where('commission_id', $request->set_up_id)->first();
            if (!blank(@$old) && @$set_up_detail->id != @$old->id) {
                return $this->responseWithError(_trans('message.Already added'), 'id', 400);
            }
            $set_up_detail->commission_id   =  $request->set_up_id;
            $set_up_detail->amount          =  $request->amount;
            $set_up_detail->amount_type     =  $request->type;
            $set_up_detail->status_id       =  $request->status;
            $set_up_detail->updated_by      =  auth()->user()->id;
            $set_up_detail->save();

            return $this->responseWithSuccess(_trans('message.Commission update successfully.'), $salary_set_up);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
