<?php

namespace App\Repositories;

use App\Models\Hrm\Attendance\Weekend;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class WeekendsRepository
{
    use RelationshipTrait, ApiReturnFormatTrait;

    protected $model;

    public function __construct(Weekend $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return $this->model->query()->with('status')->where('company_id', $this->companyInformation()->id)->get();
    }

    public function update($request)
    {
        $this->model->where('id', $request->weekend_id)->update([
            'is_weekend' => $request->is_weekend,
            'status_id' => $request->status_id
        ]);
    }

    // new functions
    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Name'),
            _trans('attendance.Weekend'),
            _trans('common.Status'),
            _trans('common.Action'),
        ];
    }

    function table($request)
    {

        
        $data = $this->model->query()->where('company_id', $this->companyInformation()->id);
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
                if (hasPermission('weekend_update')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('weekendSetup.show', $data->id) . '`)', 'modal');
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
                $is_weekend = 'danger';
                if ($data->is_weekend === 'no') {
                    $is_weekend = 'success';
                }
                return [
                    'id'         => $data->id,
                    'name'       => ucfirst($data->name),
                    'weekend'    => '<span class="badge badge-' . $is_weekend . '">' . ucfirst($data->is_weekend) . '</span>',
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

    function editAttributes($model)
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
                'value' => @$model->name,
            ],
            'is_weekend' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'is_weekend',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Is Weekend'),
                'options' => [
                    [
                        'text' => _trans('common.Yes'),
                        'value'  => 'yes',
                        'active' => $model->is_weekend == 'yes' ? true : false,
                    ],
                    [
                        'text' => _trans('common.No'),
                        'value'  => 'no',
                        'active' => $model->is_weekend == 'no' ? true : false,
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
                        'active' => $model->status_id == 1 ? true : false,
                    ],
                    [
                        'text' => _trans('payroll.Inactive'),
                        'value'  => 4,
                        'active' => $model->status_id == 4 ? true : false,
                    ]
                ]
            ]

        ];
    }

    function newUpdate($request, $id)
    {
        try {
            $weekendModel = $this->model->where('company_id', auth()->user()->company_id)->find($id);
            if ($this->isExistsWhenUpdate($weekendModel,$this->model, 'name', $request->name)) {
                $weekendModel->is_weekend = $request->is_weekend;
                $weekendModel->status_id = $request->status;
                $weekendModel->save();
                return $this->responseWithSuccess(_trans('message.Weekend update successfully.'), 200);
            } else {
                return $this->responseWithError(_trans('message.Data already exists'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
