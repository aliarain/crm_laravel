<?php

namespace App\Repositories\Leads;

use App\Models\Leads\Lead;
use App\Models\Leads\LeadStatus;
use App\Models\Management\Client;
use Illuminate\Support\Facades\DB;
use App\Models\Traits\CompanyTrait;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

class LeadStatusRepository extends BaseRepository
{
    use FileHandler, CompanyTrait, ApiReturnFormatTrait;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return LeadStatus::class;
    }
 
    public function storeStatus($request)
    {
        try {
            DB::beginTransaction();

            $status = new $this->model();
            $status->title = $request->title;
            $status->title        = $request->title;
            $status->order        = $request->order??0;
            $status->border_color = $request->border_color;
            $status->background_color     = $request->background_color;
            $status->text_color    = $request->text_color;
            $status->status_id    = $request->status;
            $status->company_id   = auth()->user()->company_id;
            $status->created_by   = auth()->user()->id;
            $status->updated_by   =  auth()->user()->id;
            $status->save();

            DB::commit();
            return true;
        } catch (\Throwable$th) {
            DB::rollBack();
            return false;
        }
    }
    public function updateStatus($request)
    {
        try {
            DB::beginTransaction();

            $status = $this->getById($request->id);
            $status->title = $request->title;
            $status->title        = $request->title;
            $status->border_color = $request->border_color;
            $status->background_color     = $request->background_color;
            $status->text_color    = $request->text_color;
            $status->order        = $request->order??0;
            $status->status_id    = $request->status;
            $status->company_id   = auth()->user()->company_id;
            $status->created_by   = auth()->user()->id;
            $status->updated_by   =  auth()->user()->id;
            $status->save();
            DB::commit();
            return true;
        } catch (\Throwable$th) {
            DB::rollBack();
            return false;
        }
    }

  
    public function deleteStatus($id)
    {
        try {
            DB::beginTransaction();

            $client = $this->getById($id);
            $client->delete();
            DB::commit();
            return true;
        } catch (\Throwable$th) {
            DB::rollBack();
            return false;
        }
    }

    // new function for
    public function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Title'),
            _trans('common.Order'),
            _trans('common.Border Color'),
            _trans('common.BG Color'),
            _trans('common.Text Color'),
            _trans('common.Status'),
            _trans('common.Action'),
        ];
    }
    public function table($request)
    {
        
        $data = $this->model->query()->where('company_id', auth()->user()->company_id);
        if (@$request->from && @$request->to) {
            $data = $data->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if ($request->search) {
            $data = $data->where('title', 'like', '%' . $request->search . '%');
        }

        $data = $data->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('lead_status_update')) {
                    $action_button .=  actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('status.edit', $data->id) . '`)', 'modal');
                }
                if (hasPermission('lead_status_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__deleteAlert(`' . route('status.delete', $data->id) . '`)', 'delete');
                }
                $button = 
                ' <div class="dropdown dropdown-action">
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
                    'title' => '<p class="text-normal">'.@$data->title.'</p>', 
                    'order' => '<p class="text-normal">'.@$data->order.'</p>', 
                    'border_color' => '<p class="text-normal" style="color:'.@$data->border_color.'">'.@$data->border_color.'</p>', 
                    'background_color' => '<p class="text-normal" style="color:'.@$data->background_color.'">'.@$data->background_color.'</p>', 
                    'text_color' => '<p class="text-normal" style="color:'.@$data->text_color.'">'.@$data->text_color.'</p>', 
                    'status' => '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>',
                    'action' => $button,
                ];
            }),
            'pagination' => [
                'total' => $data->total(),
                'count' => $data->count(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
                'pagination_html' => $data->links('backend.pagination.custom')->toHtml(),
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
        } catch (\Throwable$th) {
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
        } catch (\Throwable$th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }



    public function getStatusById($id)
    {
        return $this->model->find($id);
    }
}
