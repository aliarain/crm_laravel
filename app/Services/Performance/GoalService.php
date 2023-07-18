<?php

namespace App\Services\Performance;

use App\Models\Performance\Goal;
use App\Services\Core\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\CurrencyTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\InvoiceGenerateTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class GoalService extends BaseService
{
    use RelationshipTrait, DateHandler, InvoiceGenerateTrait, CurrencyTrait, ApiReturnFormatTrait;

    public function __construct(Goal $goal)
    {
        $this->model = $goal;
    }
    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Goal Type'),
            _trans('common.Subject'),
            _trans('performance.Target Achievement'),
            _trans('common.Rating'),
            _trans('performance.Progress'),
            _trans('common.Start Date'),
            _trans('common.End Date'),
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
            $files = $files->where('subject', 'like', '%' . $request->search . '%');
        }
        $files = $files->paginate($request->limit ?? 2);
        return [
            'data' => $files->map(function ($data) {
                $action_button = '';
                if (hasPermission('performance_goal_edit')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('performance.goal.edit', $data->id) . '`)', 'modal');
                }
                if (hasPermission('performance_goal_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`admin/performance/goal/delete/`)', 'delete');
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
                    'goal_type' => $data->goalType->name,
                    'subject' => $data->subject,
                    'target' => $data->target,
                    'progress' => $data->progress . '%' ,
                    'rating' => view('backend.performance.rating_show', compact('data')) . '(' . $data->rating . ')',
                    'start_date' => showDate($data->start_date),
                    'end_date' => showDate($data->end_date),
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
        try {
            $goal                           = new $this->model;
            $goal->description              = @$request->description;
            $goal->target                   = $request->target;
            $goal->subject                  = $request->subject;
            $goal->start_date               = $request->start_date;
            $goal->end_date                 = $request->end_date;
            $goal->goal_type_id             = $request->goal_type_id;
            $goal->company_id               = auth()->user()->company_id;
            $goal->created_by               = auth()->id();
            $goal->save();
            return $this->responseWithSuccess(_trans('message.Goal created successfully.'), $goal);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $goal = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
            if (!$goal) {
                return $this->responseWithError(_trans('message.Goal not found'), 'id', 404);
            }
            $goal->description              = @$request->description;
            $goal->target                   = $request->target;
            $goal->subject                  = $request->subject;
            $goal->start_date               = $request->start_date;
            $goal->end_date                 = $request->end_date;
            $goal->goal_type_id             = $request->goal_type_id;
            $goal->status_id                = $request->status;
            $goal->rating                   = $request->rating;
            $goal->progress                 = $request->progress;
            $goal->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Goal Updated successfully.'), $goal);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function delete($id)
    {
        $goal = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
        if (!$goal) {
            return $this->responseWithError(_trans('message.Goal not found'), 'id', 404);
        }
        try {
            $goal->delete();
            return $this->responseWithSuccess(_trans('message.Goal Delete successfully.'), $goal);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $goal = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                if ($goal) {
                    return $this->responseWithSuccess(_trans('message.Goal activate successfully.'), $goal);
                }else{
                    return $this->responseWithError(_trans('message.Goal not found'), [], 400);                
                }
            }else {
                return $this->responseWithError(_trans('message.Goal not found'), [], 400);                
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }

    }


}
