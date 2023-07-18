<?php

namespace  App\Repositories\Hrm\Finance;

use Illuminate\Support\Facades\Auth;
use App\Models\Expenses\PaymentMethod;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
//use Your Model

/**
 * Class PaymentMethodsRepository.
 */
class PaymentMethodsRepository
{
    use RelationshipTrait, FileHandler, ApiReturnFormatTrait;
    /**
     * @return string
     *  Return the model
     */

    public function __construct(PaymentMethod $model)
    {
        $this->model = $model;
    }

    public function model($filter = null)
    {
        $model = $this->model;
        if (@$filter) {
            $model = $this->model->where($filter);
        }
        return $model;
    }

    function fields()
    {
        return [
            _trans('account.ID'),
            _trans('account.Name'),
            _trans('account.Status'),
            _trans('account.Action'),
        ];
    }




    public function datatable()
    {
        $content = $this->model->query()->where('company_id', Auth::user()->company_id);
        // $content = $this->model->query()->where('status_id', 1);
        if (isset($params)) {
            $content = $content->where($params);
        }
        return datatables()->of($content->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                if (hasPermission('payment_method_edit')) {
                    $action_button .= '<a href="' . route('hrm.payment_method.edit', $data->id) . '" class="dropdown-item"> Edit</a>';
                }
                if (hasPermission('payment_method_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/payment-methods/delete/`)', 'delete');
                }
                $button = getActionButtons($action_button);
                return $button;
            })
       
            ->addColumn('name', function ($data) {
                return @$data->name;
            })
            ->addColumn('attachment', function ($data) {
                if (@$data->attachments) {
                    return '<a href="' . uploaded_asset($data->attachment_file_id) . '" target="_blank">' . 'View File' . '</a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
        
            ->rawColumns(array('name', 'status', 'action'))
            ->make(true);
    }

    public function store($request)
    {
        $payment = $this->model([
            'name' => $request->name,
            'company_id' => auth()->user()->company_id
        ])->first();
        if ($payment) {
            return $this->responseWithError(_trans('message.Payment method already exists'), 'name', 422);
        }
        try {
            $payment             = new $this->model;
            $payment->company_id = auth()->user()->company_id;
            $payment->name       = $request->name;
            $payment->status_id  = $request->status;
            $payment->save();
            return $this->responseWithSuccess(_trans('message.Payment method created successfully.'), $payment);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function edit($id)
    {
        return $this->model->query()->find($id);
    }

    public function update($request, $id)
    {

        $payment = $this->model([
            'id' => $id,
            'company_id' => auth()->user()->company_id
        ])->first();
        if (!$payment) {
            return $this->responseWithError(_trans('message.Payment method not found'), 'name', 422);
        }
        try {
            $payment->name       = $request->name;
            $payment->status_id  = $request->status;
            $payment->save();
            return $this->responseWithSuccess(_trans('message.Payment method updated successfully.'), $payment);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function destroy($id, $company_id)
    {
        $payment = $this->model([
            'id' => $id,
            'company_id' => $company_id
        ])->first();
        if (!$payment) {
            return $this->responseWithError(_trans('message.Payment method not found'), 'id', 404);
        }
        try {
            $payment->delete();
            return $this->responseWithSuccess(_trans('message.Payment method delete successfully.'), $payment);
        } catch (\Throwable $th) {
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
        $this->model->find($id)->delete();
    }

    // new Functions
    function table($request)
    {
        $data = $this->model->query()->where('company_id', Auth::user()->company_id);
        if (@$request->from && @$request->to) {
            $data = $data->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if ($request->search) {
            $data = $data->where('name', 'like', '%' . $request->search . '%');
        }
        $data = $data->orderBy('id', 'desc')->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('payment_method_edit')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('hrm.payment_method.edit', $data->id) . '`)', 'modal');
                }
                if (hasPermission('payment_method_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/payment-methods/delete/`)', 'delete');
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
                    'name' => @$data->name,
                    'status' => '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>',
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
                $category = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Payment method activate successfully.'), $category);
            }
            if (@$request->action == 'inactive') {
                $category = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Payment method inactivate successfully.'), $category);
            }
            return $this->responseWithError(_trans('message.Payment method failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $category = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                return $this->responseWithSuccess(_trans('message.Payment method delete successfully.'), $category);
            } else {
                return $this->responseWithError(_trans('message.Payment method not found'), [], 400);
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
