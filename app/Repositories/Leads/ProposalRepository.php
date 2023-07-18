<?php

namespace App\Repositories\Leads;

use App\Models\Leads\Lead;
use App\Models\Leads\LeadSource;
use App\Models\Management\Client;
use Illuminate\Support\Facades\DB;
use App\Models\Traits\CompanyTrait;
use Illuminate\Support\Facades\Log;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

/**
 * Class ClientRepository.
 */
class ProposalRepository extends BaseRepository
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


    public function getActiveAll()
    {
        return $this->model::query()->where('status_id', 1)->where('company_id', $this->companyInformation()->id)->get();
    }

 
    public function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Source'), 
            _trans('common.Status'),
            _trans('common.Action'),
        ];
    }

    public function table($request)
    {
        
        $data = $this->model->query()->where('company_id', auth()->user()->company_id);
     
        if ($request->search) {
            $data = $data->where('name', 'like', '%' . $request->search . '%');
        }
        $data = $data->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('client_update')) {
                    $action_button .= '<a href="' . route('client.edit', $data->id) . '" class="dropdown-item"> ' . _trans('common.Edit') . '</a>';
                }
                if (hasPermission('client_delete')) {
                    $action_button .= '<a href="' . route('client.delete', $data->id) . '" class="dropdown-item"> ' . _trans('common.Delete') . '</a>';
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
                    'name' => @$data->name, 
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

}