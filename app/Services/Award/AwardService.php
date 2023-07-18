<?php

namespace App\Services\Award;

use App\Models\Award\Award;
use App\Services\Core\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Helpers\CoreApp\Traits\CurrencyTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\InvoiceGenerateTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class AwardService extends BaseService
{
    use RelationshipTrait, DateHandler, FileHandler, InvoiceGenerateTrait, CurrencyTrait, ApiReturnFormatTrait;
    
    public function __construct(Award $model)
    {
        $this->model = $model;
    }

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Employee'),
            _trans('award.Award Type'),
            _trans('award.Gift'),
            _trans('common.Amount'),
            _trans('project.Date'),
            _trans('common.Status'),
            _trans('common.Action')

        ];
    }
    function userDatatable($request,$user_id)
    {
        $award =  $this->model->with('user','status','type')->where(['company_id' => auth()->user()->company_id]);
        
        $award = $award->where('user_id', $user_id)->paginate($request->limit ?? 10);

        return $this->generateDatatable($award);
    }

    function table($request)
    {
        $award =  $this->model->query()->with('user','status','type')->where(['company_id' => auth()->user()->company_id]);
        if (!is_Admin()) {
            $award = $award->where('user_id', auth()->user()->id);
        }
        if ($request->user_id) {
            $award = $award->where('user_id', $request->user_id);
        }
        if ($request->from && $request->to) {
            $award = $award->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        $award = $award->paginate($request->limit ?? 10);

        return $this->generateDatatable($award);
        
    }
    function generateDatatable($award)
    {
        return [
            'data' => $award->map(function ($data) {
                $action_button = '';

                if (hasPermission('award_view')) {
                    $action_button .= '<a href="' . route('award.view', [$data->id]) . '" class="dropdown-item">  <span class="icon mr-8"><i class="fa-solid fa-eye"></i></span>' . _trans('common.View') . '</a>';
                }
                
                if (hasPermission('award_edit')) {
                    $action_button .= '<a href="' . route('award.edit', [$data->id]) . '" class="dropdown-item">  <span class="icon mr-8"><i class="fa-solid fa-pen-to-square"></i></span>' . _trans('common.Edit') . '</a>';
                }
                if (hasPermission('award_delete')) {
                    $action_button .= actionButton( _trans('common.Delete'), '__globalDelete(' . $data->id . ',`admin/award/delete/`)', 'delete');
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
                    'name' => $data->user->name,
                    'date' => showDate($data->created_at),
                    'type' => $data->type->name,
                    'gift' => $data->gift,
                    'status' => '<small class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</small>',
                    'amount' => showAmount($data->amount),
                    'action'   => $button
                ];
            }),
            'pagination' => [
                'total' => $award->total(),
                'count' => $award->count(),
                'per_page' => $award->perPage(),
                'current_page' => $award->currentPage(),
                'total_pages' => $award->lastPage(),
                'pagination_html' =>  $award->links('backend.pagination.custom')->toHtml(),
            ],
        ];
    }

    function store($request)
    {
        DB::beginTransaction();
        try {
            $award                           = new $this->model;
            $award->award_type_id            = $request->award_type;
            $award->user_id                  = $request->user_id;
            $award->date                     = $request->date;
            $award->gift                     = $request->gift;
            $award->status_id                = $request->status;
            $award->description              = $request->content;
            $award->amount                   = $request->amount;
            $award->gift_info                = $request->award_info;
            $award->company_id               = auth()->user()->company_id;
            $award->created_by               = auth()->id();
            if ($request->hasFile('attachment')) {
                $award->attachment = $this->uploadImage($request->attachment, 'award/files')->id;
            }
            $award->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Award created successfully.'), $award);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $award = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
            if (!$award) {
                return $this->responseWithError(_trans('message.Award not found'), 'id', 404);
            }
            $award->award_type_id            = $request->award_type;
            $award->user_id                  = $request->user_id;
            $award->date                     = $request->date;
            $award->gift                     = $request->gift;
            $award->status_id                = $request->status;
            $award->description              = $request->content;
            $award->amount                   = $request->amount;
            $award->gift_info                = $request->award_info;
            $award->goal_id                  = @$request->goal_id;
            if ($request->hasFile('attachment')) {
                $this->deleteImage(asset_path($award->attachment));
                $award->attachment = $this->uploadImage($request->attachment, 'award/files')->id;
            }
            $award->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Award Updated successfully.'), $award);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function delete($id)
    {
        $award = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
        if (!$award) {
            return $this->responseWithError(_trans('message.Award not found'), 'id', 404);
        }
        try {
            if (@$award->attachment) {
                $this->deleteImage(asset_path($award->attachment));
            }
            $award->delete();            
            return $this->responseWithSuccess(_trans('message.Award Delete successfully.'), $award);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

      // statusUpdate
      public function statusUpdate($request){
        try {
            
            if (@$request->action == 'active') {
               $award = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 1]);
               return $this->responseWithSuccess(_trans('message.Award activate successfully.'), $award);
            }
            if (@$request->action == 'inactive') {
                $award = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Award inactivate successfully.'), $award);
            }
            return $this->responseWithError(_trans('message.Award failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $awards = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->get();
                foreach ($awards as $award){
                    if (@$award->attachment) {
                        $this->deleteImage(asset_path($award->attachment));
                    }
                    $award->delete(); 
                }
                return $this->responseWithSuccess(_trans('message.Award delete successfully.'), $awards);
            }else {
                return $this->responseWithError(_trans('message.Award not found'), [], 400);                
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }

    }
}