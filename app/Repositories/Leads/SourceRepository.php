<?php

namespace App\Repositories\Leads;

use App\Models\Leads\Lead;
use App\Models\Leads\LeadSource;
use App\Models\Management\Client;
use Illuminate\Support\Facades\DB;
use App\Models\Traits\CompanyTrait;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

/**
 * Class ClientRepository.
 */
class SourceRepository extends BaseRepository
{
    use FileHandler, CompanyTrait, ApiReturnFormatTrait, RelationshipTrait;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return LeadSource::class;
    }

    public function getSourceById($id)
    {
        return $this->model->find($id);
    }

    public function getActiveAll()
    {
        return $this->model::query()->where('status_id', 1)->where('company_id', $this->companyInformation()->id)->get();
    }

 
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
     
        if ($request->search) {
            $data = $data->where('title', 'like', '%' . $request->search . '%');
        }
        $data = $data->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('lead_source_update')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('source.edit', $data->id) . '`)', 'modal');
                }
                if (hasPermission('lead_source_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__deleteAlert(`' . route('source.delete', $data->id) . '`)', 'delete');
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

                $user_image = '';
                $user_image .= '<img data-toggle="tooltip" data-placement="top" title="' . $data->name . '" src="' . uploaded_asset($data->avatar_id) . '" class="staff-profile-image-small" >';
                return [
                    'id' => $data->id,
                    'title' => @$data->title, 
                    'order' => @$data->order, 
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


    public function storeSource($request)
    {
        try {
            DB::beginTransaction();

            $source               = new $this->model();
            $source->title        = $request->title;
            $source->status_id    = $request->status;
            $source->company_id   = auth()->user()->company_id;
            $source->order    = $request->order??0;
            $source->created_by   = auth()->user()->id;
            $source->updated_by   =  auth()->user()->id;
            $source->save();
            DB::commit();
            return true;
        } catch (\Throwable$th) {
            DB::rollBack();
            return false;
        }
    }
    public function updateSource($request)
    {
        try {
            DB::beginTransaction();

            $source = $this->getSourceById($request->id);
            $source->title        = $request->title;
            $source->status_id    = $request->status;
            $source->order    = $request->order??0;

            $source->company_id   = auth()->user()->company_id;
            $source->updated_by   =  auth()->user()->id;
            $source->save();
            DB::commit();
            return true;
        } catch (\Throwable$th) {
            DB::rollBack();
            return false;
        }
    }

  
    public function deleteSource($id)
    {
        try {
            DB::beginTransaction();

            $source = $this->getSourceById($id);
            $source->delete();
            DB::commit();
            return true;
        } catch (\Throwable$th) {
            DB::rollBack();
            return false;
        }
    }

    
}