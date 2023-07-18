<?php

namespace App\Services\Performance;

use App\Services\Core\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Performance\Indicator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\CurrencyTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\InvoiceGenerateTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class IndicatorService extends BaseService
{
    use RelationshipTrait, DateHandler, InvoiceGenerateTrait, CurrencyTrait, ApiReturnFormatTrait;

    public function __construct(Indicator $indicator)
    {
        $this->model = $indicator;
    }
    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Title'),
            _trans('common.Department'),
            _trans('common.Designation'),
            _trans('common.Shift'),
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
            $files = $files->where('department_id', request()->get('department'));
        }
        $files = $files->paginate($request->limit ?? 2);
        return [
            'data' => $files->map(function ($data) {
                $action_button = '';
                if (hasPermission('performance_indicator_view')) {
                    $action_button .= actionButton(_trans('common.View'), 'mainModalOpen(`' . route('performance.indicator.view', $data->id) . '`)', 'modal');
                }
                if (hasPermission('performance_indicator_edit')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('performance.indicator.edit', $data->id) . '`)', 'modal');
                }
                if (hasPermission('performance_indicator_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`admin/performance/indicator/delete/`)', 'delete');
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
                    'department' => $data->department->title,
                    'designation' => $data->designation->title,
                    'shift' => $data->shift->name,
                    'rating' => view('backend.performance.rating_show', compact('data')) . '(' . $data->rating . ')',
                    'added_by' => $data->added->name,
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
            if ($this->isExistsWhenStore( $this->model, 'name', $request->title)) {
                $rates = 0;
                $rates_json = [];
                $indicator                           = new $this->model;
                $indicator->name                    = $request->title;
                $indicator->department_id            = $request->department_id;
                $indicator->designation_id           = $request->designation_id;
                $indicator->shift_id                 = $request->shift_id;
                $indicator->company_id               = auth()->user()->company_id;
                $indicator->added_by                 = auth()->id();
                if ($request->has('rating')) {
                    foreach ($request->get('rating') as $key => $value) {
                        $rates += $value;
                        $rates_json[] = [
                            'rating' => $value,
                            'id' => $key
                        ];
                    }
                }
                $indicator->rating = $rates / count($request->get('rating'));
                $indicator->rates = $rates_json;
                $indicator->save();
                DB::commit();
                return $this->responseWithSuccess(_trans('message.Indicator created successfully.'), $indicator);
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
            $indicator = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
            if (!$indicator) {
                return $this->responseWithError(_trans('message.Indicator not found'), 'id', 404);
            }
            if ($this->isExistsWhenUpdate($indicator, $this->model, 'name', $request->title)) {

                $rates = 0;
                $rates_json = [];
                $indicator->name                    = $request->title;
                $indicator->department_id            = $request->department_id;
                $indicator->designation_id           = $request->designation_id;
                $indicator->shift_id                 = $request->shift_id;
                if ($request->has('rating')) {
                    foreach ($request->get('rating') as $key => $value) {
                        $rates += $value;
                        $rates_json[] = [
                            'rating' => $value,
                            'id' => $key
                        ];
                    }
                }
                $indicator->rating = $rates / count($request->get('rating'));
                $indicator->rates = $rates_json;
                $indicator->save();
                DB::commit();
                return $this->responseWithSuccess(_trans('message.Indicator Updated successfully.'), $indicator);
            } else {
                return $this->responseWithError(_trans('message.Data already exists'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function delete($id)
    {
        $indicator = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
        if (!$indicator) {
            return $this->responseWithError(_trans('message.Indicator not found'), 'id', 404);
        }
        try {
            $indicator->delete();
            return $this->responseWithSuccess(_trans('message.Indicator Delete successfully.'), $indicator);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $indicator = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                if ($indicator) {
                    return $this->responseWithSuccess(_trans('message.Indicator activate successfully.'), $indicator);
                } else {
                    return $this->responseWithError(_trans('message.Indicator not found'), [], 400);
                }
            } else {
                return $this->responseWithError(_trans('message.Indicator not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function newStore($request)
    {
        try {
            if ($this->isExistsWhenStore($this->designation, 'title', $request->title)) {
                $designation = new $this->designation;
                $designation->title = $request->title;
                $designation->status_id = $request->status;
                $designation->company_id = auth()->user()->company_id;
                $designation->save();
                $this->createdBy($designation);
                return $this->responseWithSuccess(_trans('message.Designation store successfully.'), 200);
            } else {
                return $this->responseWithError(_trans('message.Data already exists'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
