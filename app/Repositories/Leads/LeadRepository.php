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
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

/**
 * Class ClientRepository.
 */
class LeadRepository extends BaseRepository
{
    use FileHandler, CompanyTrait, ApiReturnFormatTrait;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Lead::class;
    }
 
    public function getProfile($id)
    {
        return $this->getById($id);
    }
    public function storeLead($request)
    {
        try {
            DB::beginTransaction();
            $lead = new $this->model();
            $lead->name = $request->name;
            $lead->company = auth()->user()->company->name;
            $lead->title = $request->title;
            $lead->description = $request->description;
            $lead->country = $request->country_id;
            $lead->state = $request->state;
            $lead->city = $request->city;
            $lead->zip = $request->zip;
            $lead->address = $request->address;
            $lead->email = $request->email;
            $lead->phone = $request->phone;
            $lead->website = $request->website;
            $lead->company_id = auth()->user()->company_id;
            $lead->date = date('Y-m-d');
            $lead->next_follow_up = $request->next_follow_up;
            $lead->lead_type_id = $request->lead_type_id;
            $lead->lead_source_id = $request->lead_source_id;
            $lead->lead_status_id = $request->lead_status_id;
            $lead->status_id = $request->status_id;
            $lead->created_by = auth()->user()->id;

            $lead->activities = "[]";
            $lead->attachments = "[]";
            $lead->emails = "[]";
            $lead->calls = "[]";
            $lead->notes = "[]";
            $lead->tags = "[]";
            $lead->tasks = "[]";
            $lead->reminders = "[]";

            $lead->save();
            try{
                Lead::CreateActivity($lead, $request, 'Lead created successfully');
            }catch(\Throwable$th){
            }

            DB::commit();
            return true;
        } catch (\Throwable$th) {
            DB::rollBack();
            return false;
        }
    }
    public function updateLead($request)
    {
        try {
            DB::beginTransaction();

            $lead = $this->getLeadById($request->id);
            $lead->name = $request->name;
            $lead->company = auth()->user()->company->name;
            $lead->title = $request->title;
            $lead->description = $request->description;
            $lead->country = $request->country_id;
            $lead->state = $request->state;
            $lead->city = $request->city;
            $lead->zip = $request->zip;
            $lead->address = $request->address;
            $lead->email = $request->email;
            $lead->phone = $request->phone;
            $lead->website = $request->website;
            $lead->company_id = auth()->user()->company_id;
            $lead->date = date('Y-m-d');
            $lead->next_follow_up = $request->next_follow_up;
            $lead->lead_type_id = $request->lead_type_id;
            $lead->lead_source_id = $request->lead_source_id;
            $lead->lead_status_id = $request->lead_status_id;
            $lead->status_id = $request->status_id;
            $lead->save();

            try{
                Lead::CreateActivity($lead, $request, 'Lead updated successfully');
            }catch(\Throwable$th){
            }
            DB::commit();
            return true;
        } catch (\Throwable$th) {
            DB::rollBack();
            return false;
        }
    }

  
    public function deleteLead($id)
    {
        try {
            DB::beginTransaction();

            $lead = $this->getLeadById($id);
            $lead->delete();
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
            _trans('common.Type'),
            _trans('common.Source'),
            _trans('common.Step'),
            _trans('common.Date'),
            _trans('common.Name'),
            _trans('common.Title'),
            _trans('common.Company'),
            _trans('common.Address'),
            _trans('common.Next Follow Up'),

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
            $data = $data->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->lead_types) {
            $data = $data->where('lead_type_id', $request->lead_type);
        }
        if ($request->lead_sources) {
            $data = $data->where('lead_source_id', $request->lead_source);
        }
        if ($request->lead_statuses) {
            $data = $data->where('lead_status_id', $request->lead_statuses);
        }




        $data = $data->latest()->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('lead_view')) {
                    $action_button .= '<a href="' . route('lead.leadDetails', $data->id) . '" class="dropdown-item"> <span class="icon mr-8"><i class="fa-solid fa-eye"></i></span>' . _trans('common.View') . '</a>';
                }
                if (hasPermission('lead_update')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('lead.edit', $data->id) . '`)', 'modal');
                }
                if (hasPermission('lead_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__deleteAlert(`' . route('lead.delete', $data->id) . '`)', 'delete');
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
                    'type' => @$data->type->title,
                    'source' => @$data->source->title,
                    'project_status' => '<span class="badge" style="min-width:100px;color:'. @$data->lead_status->background_color.';border: 1px solid '. @$data->lead_status->border_color.';">' .  @$data->lead_status->title . '</span>',
                    'date' => date('d M, Y', strtotime($data->date)),
                    // 'name' => actionButton($data->name, 'mainModalOpen(`' . route('lead.leadDetails', $data->id) . '`)', 'modal'),
                    'name' => '<a href="' . route('lead.leadDetails', $data->id).'">' .$data->name . '</a>',
                    'title' => '<p class="text-normal">'.@$data->title.'</p>', 
                    'company' => '<p class="text-normal">'.@$data->company.'</p>', 
                    'address' => '<p class="text-normal">'.@$data->address.'</p>',
                    'next_follow_up' =>  $data->next_follow_up ? date('d M, Y', strtotime($data->next_follow_up)) : 'N/A',
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


    // getLeadSources
    public function getLeadSources()
    {
        return LeadSource::where('company_id', auth()->user()->company_id)->get();
    }

    public function totalLead($id)
    {
        return $totalLead = $this->model->where('company_id', auth()->user()->company_id)->where('created_by',auth()->user()->id)->count();
    }
}
