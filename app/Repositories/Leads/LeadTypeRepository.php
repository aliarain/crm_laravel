<?php

namespace App\Repositories\Leads;

use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Models\Leads\Lead;
use App\Models\LeadType;
use App\Models\Management\Client;
use App\Models\Traits\CompanyTrait;
use Illuminate\Support\Facades\DB;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

/**
 * Class ClientRepository.
 */
class LeadTypeRepository extends BaseRepository
{
    use FileHandler, CompanyTrait, ApiReturnFormatTrait;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return LeadType::class;
    }
 
    public function storeType($request)
    {
        try {
            DB::beginTransaction();

            $type               = new $this->model();
            $type->title        = $request->title;
            $type->status_id    = $request->status;
            $type->order    = $request->order??0;

            $type->company_id   = auth()->user()->company_id;
            $type->created_by   = auth()->user()->id;
            $type->updated_by   =  auth()->user()->id;
            $type->save();
            DB::commit();
            return true;
        } catch (\Throwable$th) {
            DB::rollBack();
            return false;
        }
    }
    public function updateType($request)
    {
        try {
            DB::beginTransaction();

            $type = $this->getLeadById($request->id);
            $type->title        = $request->title;
            $type->status_id    = $request->status;
            $type->company_id   = auth()->user()->company_id;
            $type->order    = $request->order??0;
            $type->updated_by   =  auth()->user()->id;
            $type->save();
            DB::commit();
            return true;
        } catch (\Throwable$th) {
            DB::rollBack();
            return false;
        }
    }

  
    public function deleteType($id)
    {
        try {
            DB::beginTransaction();

            $type = $this->getLeadById($id);
            $type->delete();
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
                if (hasPermission('lead_type_update')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('type.edit', $data->id) . '`)', 'modal');
                }
                if (hasPermission('lead_type_delete')) { 
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`admin/types/delete/`)', 'delete');
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



    public function getLeadById($id)
    {
        return $this->model->find($id);
    }
}
