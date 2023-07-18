<?php

namespace App\Services\Award;

use App\Models\Award\AwardType;
use App\Services\Core\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\CurrencyTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\InvoiceGenerateTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use Illuminate\Support\Facades\Log;

class AwardTypeService extends BaseService
{
    use RelationshipTrait, DateHandler, InvoiceGenerateTrait, CurrencyTrait, ApiReturnFormatTrait;

    public function __construct(AwardType $awardType)
    {
        $this->model = $awardType;
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
                if (hasPermission('award_type_edit')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('award_type.edit', $data->id) . '`)', 'modal');
                }
                if (hasPermission('award_type_delete')) {
                    $action_button .= actionButton( _trans('common.Delete'), '__globalDelete(' . $data->id . ',`admin/award/type/delete/`)', 'delete');
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
            $award_type                           = new $this->model;
            $award_type->name                     = $request->name;
            $award_type->status_id                = $request->status;
            $award_type->company_id               = auth()->user()->company_id;
            $award_type->created_by               = auth()->id();
            $award_type->updated_by               = auth()->id();
            $award_type->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Award type created successfully.'), $award_type);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $award_type = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
            if (!$award_type) {
                return $this->responseWithError(_trans('message.Award Type not found'), 'id', 404);
            }
            $award_type->name                     = $request->name;
            $award_type->status_id                = $request->status;
            $award_type->updated_by               = auth()->id();
            $award_type->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Award type update successfully.'), $award_type);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function delete($id)
    {
        $award_type = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
        if (!$award_type) {
            return $this->responseWithError(_trans('message.Award type not found'), 'id', 404);
        }
        try {
            $award_type->awards()->delete();
            $award_type->delete();
            return $this->responseWithSuccess(_trans('message.Award type Delete successfully.'), $award_type);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    // statusUpdate
    public function statusUpdate($request)
    {
        try {
            
            if (@$request->action == 'active') {
                $award_type = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Award type activate successfully.'), $award_type);
            }
            if (@$request->action == 'inactive') {
                $award_type = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Award type inactivate successfully.'), $award_type);
            }
            return $this->responseWithError(_trans('message.Award type inactivate failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $award_type = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                return $this->responseWithSuccess(_trans('message.Award type delete successfully.'), $award_type);
            } else {
                return $this->responseWithError(_trans('message.Award type not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
