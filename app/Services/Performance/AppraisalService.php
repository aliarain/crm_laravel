<?php

namespace App\Services\Performance;

use App\Services\Core\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Performance\Appraisal;
use App\Models\Performance\Indicator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\CurrencyTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\InvoiceGenerateTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class AppraisalService extends BaseService
{
    use RelationshipTrait, DateHandler, InvoiceGenerateTrait, CurrencyTrait, ApiReturnFormatTrait;

    public function __construct(Appraisal $appraisal)
    {
        $this->model = $appraisal;
    }
    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Title'),
            _trans('common.Employee'),
            _trans('common.Department'),
            _trans('common.Designation'),
            _trans('common.Rating'),
            _trans('common.Added By'),
            _trans('common.Created At'),
            _trans('common.Action')

        ];
    }


    function table($request)
    {
        $files =  $this->model->where(['company_id' => auth()->user()->company_id]);
        if ($request->from && $request->to) {
            $files = $files->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if ($request->search) {
            $files = $files->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->department) {
            $files = $files->whereHas('user', function (Builder $query) use ($request) {
                $query->where('department_id', $request->department);
            });
        }
        $files = $files->paginate($request->limit ?? 2);
        return [
            'data' => $files->map(function ($data) {
                $action_button = '';
                if (hasPermission('performance_appraisal_view')) {
                    $action_button .= actionButton(_trans('common.View'), 'mainModalOpen(`' . route('performance.appraisal.view', $data->id) . '`)', 'modal');
                }
                if (hasPermission('performance_appraisal_edit')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('performance.appraisal.edit', $data->id) . '`)', 'modal');
                }
                if (hasPermission('performance_appraisal_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`admin/performance/appraisal/delete/`)', 'delete');
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
                    'title' => $data->name,
                    'department' => @$data->user->department->title,
                    'designation' => @$data->user->designation->title,
                    'user' => @$data->user->name,
                    'rating' => view('backend.performance.rating_show', compact('data')) . '(' . $data->rating . ')',
                    'added_by' => @$data->added->name,
                    'created_at' => showDate($data->created_at),
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
            if ($this->isExistsWhenStore($this->model, 'name', $request->title)) {
                $rates = 0;
                $rates_json = [];
                $appraisal                           = new $this->model;
                $appraisal->remarks                  = @$request->remarks;
                $appraisal->name                     = $request->title;
                $appraisal->date                     = $request->date ?? date('Y-m-d');
                $appraisal->user_id                  = $request->user_id;
                $appraisal->company_id               = auth()->user()->company_id;
                $appraisal->added_by                 = auth()->id();
                if ($request->has('rating')) {
                    foreach ($request->get('rating') as $key => $value) {
                        $rates += $value;
                        $rates_json[] = [
                            'rating' => $value,
                            'id' => $key
                        ];
                    }
                }
                $appraisal->rating = $rates / count($request->get('rating'));
                $appraisal->rates = $rates_json;
                $appraisal->save();
                DB::commit();
                return $this->responseWithSuccess(_trans('message.Appraisal created successfully.'), $appraisal);
            } else {
                return $this->responseWithError(_trans('message.Data already exists'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $appraisal = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
            if (!$appraisal) {
                return $this->responseWithError(_trans('message.Appraisal not found'), 'id', 404);
            }
            if ($this->isExistsWhenUpdate($appraisal, $this->model, 'name', $request->title)) {
                $rates = 0;
                $rates_json = [];
                $appraisal->remarks                  = @$request->remarks;
                $appraisal->name                     = $request->title;
                $appraisal->date                     = $request->date ?? date('Y-m-d');
                $appraisal->user_id                  = $request->user_id;
                if ($request->has('rating')) {
                    foreach ($request->get('rating') as $key => $value) {
                        $rates += $value;
                        $rates_json[] = [
                            'rating' => $value,
                            'id' => $key
                        ];
                    }
                }
                $appraisal->rating = $rates / count($request->get('rating'));
                $appraisal->rates = $rates_json;
                $appraisal->save();
                DB::commit();
                return $this->responseWithSuccess(_trans('message.Appraisal Updated successfully.'), $appraisal);
            } else {
                return $this->responseWithError(_trans('message.Data already exists'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function delete($id)
    {
        $appraisal = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
        if (!$appraisal) {
            return $this->responseWithError(_trans('message.Appraisal not found'), 'id', 404);
        }
        try {
            $appraisal->delete();
            return $this->responseWithSuccess(_trans('message.Appraisal Delete successfully.'), $appraisal);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function destroyAll($request)
    {
        try {
            
            if (@$request->ids) {
                $appraisal = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                if ($appraisal) {
                    return $this->responseWithSuccess(_trans('message.Appraisal activate successfully.'), $appraisal);
                } else {
                    return $this->responseWithError(_trans('message.Appraisal not found'), [], 400);
                }
            } else {
                return $this->responseWithError(_trans('message.Appraisal not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
