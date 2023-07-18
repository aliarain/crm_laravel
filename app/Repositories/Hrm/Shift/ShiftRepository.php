<?php

namespace App\Repositories\Hrm\Shift;

use function route;
use function datatables;
use function actionButton;
use App\Models\Hrm\Shift\Shift;
use function start_end_datetime;
use App\Models\Traits\RelationCheck;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Hrm\Designation\Designation;
use App\Helpers\CoreApp\Traits\AuthorInfoTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class ShiftRepository
{
    use AuthorInfoTrait, RelationshipTrait, RelationCheck, ApiReturnFormatTrait;

    protected Shift $shift;

    public function __construct(Shift $shift)
    {
        $this->shift = $shift;
    }

    public function getAll()
    {
        return $this->shift::query()->where('company_id', $this->companyInformation()->id)->get();
    }

    public function index()
    {
    }

    public function store($request)
    {
        $company = $this->shift->query()->create($request->all());
        $this->createdBy($company);
        return true;
    }

    public function dataTable($request, $id = null)
    {
        $shift = $this->shift->query()->with('status')->where('company_id', $this->companyInformation()->id);
        if (@$request->from && @$request->to) {
            $shift = $shift->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if (@$id) {
            $shift = $shift->where('id', $id);
        }

        return datatables()->of($shift->orderBy('id', 'DESC')->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                $edit = _trans('common.Edit');
                $delete = _trans('common.Delete');
                if (hasPermission('shift_update')) {
                    $action_button .= '<a href="' . route('shift.edit', $data->id) . '" class="dropdown-item"> ' . $edit . '</a>';
                }
                if (hasPermission('shift_delete')) {
                    $action_button .= actionButton($delete, '__globalDelete(' . $data->id . ',`hrm/shift/delete/`)', 'delete');
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

    public function show($id)
    {
    }


    public function update($request): bool
    {
        $shift = $this->shift->where('id', $request->shift_id)->first();
        $shift->name = $request->name;
        $shift->status_id = $request->status_id;
        $shift->save();
        $this->updatedBy($shift);
        return true;
    }

    public function destroy($shift)
    {
        $table_name = $this->shift->getTable();
        $foreign_id = \Illuminate\Support\Str::singular($table_name) . '_id';
        return \App\Services\Hrm\DeleteService::deleteData($table_name, $foreign_id, $shift->id);
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

        
        $data = $this->shift->query()->where('company_id', $this->companyInformation()->id);
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
                if (hasPermission('shift_update')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('shift.edit', $data->id) . '`)', 'modal');
                }
                if (hasPermission('shift_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/shift/delete/`)', 'delete');
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
                $shift = $this->shift->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Shift activate successfully.'), $shift);
            }
            if (@$request->action == 'inactive') {
                $shift = $this->shift->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Shift inactivate successfully.'), $shift);
            }
            return $this->responseWithError(_trans('message.Shift failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $shift = $this->shift->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                return $this->responseWithSuccess(_trans('message.Shift Delete successfully.'), $shift);
            } else {
                return $this->responseWithError(_trans('message.shift not found'), [], 400);
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
    function editAttributes($shift)
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
                'value' => @$shift->name,
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
                        'active' => $shift->status_id == 1 ? true : false,
                    ],
                    [
                        'text' => _trans('payroll.Inactive'),
                        'value'  => 4,
                        'active' =>  $shift->status_id == 4 ? true : false,
                    ]
                ]
            ]

        ];
    }

    function newStore($request)
    {
        try {
            if ($this->isExistsWhenStore($this->shift, 'name', $request->name)) {
                $shiftModal = new $this->shift;
                $shiftModal->name = $request->name;
                $shiftModal->status_id = $request->status;
                $shiftModal->company_id = auth()->user()->company_id;
                $shiftModal->save();
                $this->createdBy($shiftModal);
                return $this->responseWithSuccess(_trans('message.Shift store successfully.'), 200);

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
            $shiftModel = $this->shift->where('company_id', auth()->user()->company_id)->where('id', $id)->first();
            if ($this->isExistsWhenUpdate($shiftModel,$this->shift, 'name', $request->name)) {
                $shift = $shiftModel;
                $shift->name = $request->name;
                $shift->status_id = $request->status;
                $shift->save();
                $this->updatedBy($shift);
                return $this->responseWithSuccess(_trans('message.Shift update successfully.'), 200);

            } else {
                return $this->responseWithError(_trans('message.Data already exists'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
