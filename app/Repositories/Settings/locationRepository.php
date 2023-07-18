<?php

namespace App\Repositories\Settings;

use App\Models\Settings\LocationBind;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use Illuminate\Support\Facades\Log;

class locationRepository
{
    use ApiReturnFormatTrait, FileHandler;
    /**
     * @var LocationBind
     */
    protected $model;
    public function __construct(LocationBind $model)
    {
        $this->model = $model;
    }

    public function fields()
    {
        return [
            _trans('common.ID'),
            _trans('settings.Location'),
            _trans('settings.Latitude'),
            _trans('settings.Longitude'),
            _trans('settings.Distance'),
            _trans('common.Status'),
            _trans('common.Action'),
        ];
    }

    public function model($filter = null)
    {
        $model = $this->model;
        if ($filter) {
            $model = $this->model->where($filter);
        }
        return $model;
    }

    public function datatable()
    {
        $content = $this->model->query()->where('company_id', auth()->user()->company_id);
        return datatables()->of($content->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                if (hasPermission('location_edit')) {
                      $action_button .= '<a href="' . route('company.settings.locationEdit', $data->id) . '" class="dropdown-item"> ' . _trans('common.Edit') . '</a>';
                }

                if (hasPermission('location_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`admin/company-setup/location/delete/`)', 'delete');
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
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->addColumn('distance', function ($data){
                return $data->distance . 'm';
            })
            ->rawColumns(array( 'distance', 'status', 'action'))
            ->make(true);
    }

    function create($request)
    {
        try {
            $where = [
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
                'company_id'=> auth()->user()->company_id,
            ];
            $location_bind = $this->model->where($where)->first();
            if ($location_bind) {
                return $this->responseWithError(_trans('Location already exists'), 'name', 422);
            }
            $location_bind              = new $this->model;
            $location_bind->latitude    = $request->latitude;
            $location_bind->longitude   = $request->longitude;
            $location_bind->distance    = $request->distance;
            $location_bind->status_id   = $request->status;
            $location_bind->address     = $request->location;
            $location_bind->company_id  = auth()->user()->company->id;
            $location_bind->user_id     = auth()->id();
            $location_bind->save();
            return $this->responseWithSuccess(_trans('message.Location created successfully.'), $location_bind);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    function update($request, $id)
    {
        try {
            $location_bind = $this->model(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
            if (!$location_bind) {
                return $this->responseWithError(_trans('Location not found'), 'id', 422);
            }
            $location_bind->latitude    = $request->latitude;
            $location_bind->longitude   = $request->longitude;
            $location_bind->distance    = $request->distance;
            $location_bind->status_id   = $request->status;
            $location_bind->address     = $request->location;
            $location_bind->company_id  = auth()->user()->company->id;
            $location_bind->user_id     = auth()->id();
            $location_bind->save();
            return $this->responseWithSuccess(_trans('message.Location update successfully.'), $location_bind);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function delete($id)
    {
        $location = $this->model(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
        if (!$location) {
            return $this->responseWithError(_trans('Location not found'), 'id', 404);
        }
        try {
            $location->delete();
            return $this->responseWithSuccess(_trans('message.Location Delete successfully.'), $location);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function table($request)
    {

        $data = $this->model->query()->where('company_id', auth()->user()->company_id);
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
                if (hasPermission('location_edit')) {
                    $action_button .= '<a href="' . route('company.settings.locationEdit', $data->id) . '" class="dropdown-item"> <span class="icon mr-8"><i class="fa-solid fa-pen-to-square"></i></span>' . _trans('common.Edit') . '</a>';
              }

              if (hasPermission('location_delete')) {
                  $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`admin/company-setup/location/delete/`)', 'delete');
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
                    'location'       => $data->address,
                    'latitude'       => $data->latitude,
                    'longitude'       => $data->longitude,
                    'distance' => $data->distance . 'm',
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


    public function statusUpdate($request)
    {
        try {
            if (@$request->action == 'active') {
                $location = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Location activate successfully.'), $location);
            }
            if (@$request->action == 'inactive') {
                $location = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Location inactivate successfully.'), $location);
            }
            return $this->responseWithError(_trans('message.Location failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $location = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                return $this->responseWithSuccess(_trans('message.Location Delete successfully.'), $location);
            } else {
                return $this->responseWithError(_trans('message.Location not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
