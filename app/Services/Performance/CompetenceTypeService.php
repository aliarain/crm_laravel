<?php

namespace App\Services\Performance;

use App\Services\Core\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Performance\Appraisal;
use App\Models\Performance\Indicator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Models\Performance\CompetenceType;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\CurrencyTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\InvoiceGenerateTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class CompetenceTypeService extends BaseService
{
    use RelationshipTrait, DateHandler, InvoiceGenerateTrait, CurrencyTrait, ApiReturnFormatTrait;

    public function __construct(CompetenceType $competenceType)
    {
        $this->model = $competenceType;
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
                if (hasPermission('performance_competence_type_edit')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('performance.competence.type.edit', $data->id) . '`)', 'modal');
                }
                if (hasPermission('performance_competence_type_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`admin/performance/settings/competence-type/delete/`)', 'delete');
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
            $competence_type                           = new $this->model;
            $competence_type->name                     = $request->name;
            $competence_type->status_id                = $request->status;
            $competence_type->company_id               = auth()->user()->company_id;
            $competence_type->created_by               = auth()->id();
            $competence_type->updated_by               = auth()->id();
            $competence_type->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Competence type created successfully.'), $competence_type);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $competence_type = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
            if (!$competence_type) {
                return $this->responseWithError(_trans('message.Competence Type not found'), 'id', 404);
            }
            $competence_type->name                     = $request->name;
            $competence_type->status_id                = $request->status;
            $competence_type->updated_by               = auth()->id();
            $competence_type->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Competence type updated successfully.'), $competence_type);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function delete($id)
    {
        $competence_type = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
        if (!$competence_type) {
            return $this->responseWithError(_trans('message.Competence type not found'), 'id', 404);
        }
        try {
            $competence_type->competencies()->delete();
            $competence_type->delete();
            return $this->responseWithSuccess(_trans('message.Competence type Delete successfully.'), $competence_type);
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
                return $this->responseWithSuccess(_trans('message.Competence type activate successfully.'), $award_type);
            }
            if (@$request->action == 'inactive') {
                $award_type = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Competence type inactivate successfully.'), $award_type);
            }
            return $this->responseWithError(_trans('message.Competence type failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $award_type = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                return $this->responseWithSuccess(_trans('message.Competence type delete successfully.'), $award_type);
            } else {
                return $this->responseWithError(_trans('message.Competence type not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
