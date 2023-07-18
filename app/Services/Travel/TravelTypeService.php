<?php

namespace App\Services\Travel;

use App\Models\Travel\TravelType;
use App\Services\Core\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\CurrencyTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\InvoiceGenerateTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class TravelTypeService extends BaseService
{
    use RelationshipTrait, DateHandler, InvoiceGenerateTrait, CurrencyTrait, ApiReturnFormatTrait;

    public function __construct(TravelType $travelType)
    {
        $this->model = $travelType;
    }
    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('task.Name'),
            _trans('common.Status'),
            _trans('common.Action')

        ];
    }


    function table($request)
    {
        $files =  $this->model->where(['company_id' => auth()->user()->company_id]);
        if ($request->search) {
            $files = $files->where('name', 'like', '%' . $request->search . '%');
        }
        $files = $files->paginate($request->limit ?? 10);
        return [
            'data' => $files->map(function ($data) {
                $action_button = '';
                if (hasPermission('travel_type_edit')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('travel_type.edit', $data->id) . '`)', 'modal');
                }
                if (hasPermission('travel_type_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`admin/travel/type/delete/`)', 'delete');
                }
                $button = '<div class="dropdown dropdown-action">
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
                'total' => $files->total(),
                'count' => $files->count(),
                'per_page' => $files->perPage(),
                'current_page' => $files->currentPage(),
                'total_pages' => $files->lastPage(),
                'pagination_html' =>  $files->links('backend.pagination.custom')->toHtml(),
            ],
        ];
    }

    function store($request)
    {
        DB::beginTransaction();
        try {
            $travel_type                           = new $this->model;
            $travel_type->name                     = $request->name;
            $travel_type->status_id                = $request->status;
            $travel_type->company_id               = auth()->user()->company_id;
            $travel_type->created_by               = auth()->id();
            $travel_type->updated_by               = auth()->id();
            $travel_type->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Travel type created successfully.'), $travel_type);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $travel_type = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
            if (!$travel_type) {
                return $this->responseWithError(_trans('message.Travel Type not found'), 'id', 404);
            }
            $travel_type->name                     = $request->name;
            $travel_type->status_id                = $request->status;
            $travel_type->updated_by               = auth()->id();
            $travel_type->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Travel type update successfully.'), $travel_type);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function delete($id)
    {
        $travel_type = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
        if (!$travel_type) {
            return $this->responseWithError(_trans('message.Travel type not found'), 'id', 404);
        }
        try {
            $travel_type->travels()->delete();
            $travel_type->delete();
            return $this->responseWithSuccess(_trans('message.Travel type Delete successfully.'), $travel_type);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    // status Update
    public function statusUpdate($request)
    {
        try {
            if (@$request->action == 'active') {
                $travel = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Travel type activate successfully.'), $travel);
            }
            if (@$request->action == 'inactive') {
                $travel = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Travel type inactivate successfully.'), $travel);
            }
            return $this->responseWithError(_trans('message.Travel type inactivate failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $travel = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                return $this->responseWithSuccess(_trans('message.Travel type delete successfully.'), $travel);
            } else {
                return $this->responseWithError(_trans('message.Travel type not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
