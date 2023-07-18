<?php

namespace App\Repositories\Settings;

use App\Models\coreApp\Setting\IpSetup;
use App\Repositories\Interfaces\IpInterface;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class IpRepository implements IpInterface
{
    use ApiReturnFormatTrait, RelationshipTrait;

    protected $ipSetup;

    public function __construct(IpSetup $ipSetup)
    {
        $this->ipSetup = $ipSetup;
    }

    public function model($filter = null)
    {
        if ($filter) {
            return $this->ipSetup->where($filter);
        }
        return $this->ipSetup;
    }

    public function storeIp($request)
    {
        try {

            $ipconfig = new IpSetup;
            $ipconfig->location = $request->location;
            $ipconfig->ip_address = $request->ip_address;
            $ipconfig->status_id = $request->status_id;
            $ipconfig->company_id = auth()->user()->company->id;
            $ipconfig->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function updateIp($request, $id)
    {
        try {
            $ipconfig = IpSetup::where('id', $id)->first();
            $ipconfig->location = $request->location;
            $ipconfig->ip_address = $request->ip_address;
            $ipconfig->status_id = $request->status_id;
            $ipconfig->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function dataTable()
    {
        $ip_list = $this->ipSetup->query();

        return datatables()->of($ip_list->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                $edit = _trans('common.Edit');
                $delete = _trans('common.Delete');
                $action_button .= '<a href="' . route('ipConfig.show', $data->id) . '" class="dropdown-item"> ' . $edit . '</a>';

                $action_button .= actionButton($delete, '__globalDelete(' . $data->id . ',`admin/company-setup/ip-whitelist/delete/`)', 'delete');

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
            ->addColumn('location', function ($data) {
                return @$data->location;
            })
            ->addColumn('ip_address', function ($data) {
                return @$data->ip_address;
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->rawColumns(array('location', 'ip_address', 'status', 'action'))
            ->make(true);
    }

    public function showIp($id)
    {
        try {
            $ip = $this->ipSetup->find($id);
            return $ip;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function deleteIp($id)
    {
        try {
            $this->ipSetup->where('id', $id)->delete();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }



    //new functions
    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Location'),
            _trans('common.IP Address'),
            _trans('common.Status'),
            _trans('common.Action'),
        ];
    }

    function table($request)
    {

        $data = $this->ipSetup->query()->where('company_id', auth()->user()->company_id);
        if ($request->from && $request->to) {
            $data = $data->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if ($request->search) {
            $data = $data->where('location', 'like', '%' . $request->search . '%');
        }
        $data = $data->orderBy('id', 'DESC')->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('ip_update')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('ipConfig.edit', $data->id) . '`)', 'modal');
                }
                if (hasPermission('ip_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/ip-whitelist/delete/`)', 'delete');
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
                    'location'       => $data->location,
                    'address'       => $data->ip_address,
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
                $shift = $this->ipSetup->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Ip activate successfully.'), $shift);
            }
            if (@$request->action == 'inactive') {
                $shift = $this->ipSetup->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Ip inactivate successfully.'), $shift);
            }
            return $this->responseWithError(_trans('message.Ip failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $shift = $this->ipSetup->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                return $this->responseWithSuccess(_trans('message.Ip Delete successfully.'), $shift);
            } else {
                return $this->responseWithError(_trans('message.Ip not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function createAttributes()
    {
        return [
            'location' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'location',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Location')
            ],
            'ip_address' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'ip_address',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('common.IP Address')
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
    function editAttributes($designation)
    {
        return [
            'location' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'location',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Location'),
                'value' => @$designation->location,
            ],
            'ip_address' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'ip_address',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('common.IP Address'),
                'value' => @$designation->ip_address,
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
                        'active' => $designation->status_id == 1 ? true : false,
                    ],
                    [
                        'text' => _trans('payroll.Inactive'),
                        'value'  => 4,
                        'active' =>  $designation->status_id == 4 ? true : false,
                    ]
                ]
            ]

        ];
    }

    function newStore($request)
    {
        try {
            $ipconfig = new IpSetup;
            $ipconfig->location = $request->location;
            $ipconfig->ip_address = $request->ip_address;
            $ipconfig->status_id = $request->status;
            $ipconfig->company_id = auth()->user()->company->id;
            $ipconfig->save();
            return $this->responseWithSuccess(_trans('message.Ip Address store successfully.'), 200);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    function newUpdate($request, $id)
    {
        try {
            $ipconfig  = $this->ipSetup->find($id);
            $ipconfig->location = $request->location;
            $ipconfig->ip_address = $request->ip_address;
            $ipconfig->status_id = $request->status;
            $ipconfig->save();
            return $this->responseWithSuccess(_trans('message.Ip Address updated successfully.'), 200);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
