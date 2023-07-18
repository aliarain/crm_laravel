<?php

namespace App\Repositories\Hrm\Client;

use App\Models\Visit\VisitImage;
use App\Models\Management\Client;
use Illuminate\Support\Facades\DB;
use App\Models\Traits\CompanyTrait;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\Management\Project;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class ClientRepository.
 */
class ClientRepository extends BaseRepository
{
    use FileHandler, CompanyTrait, ApiReturnFormatTrait;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Client::class;
    }

    function storeClient($request)
    {
        try {
            DB::beginTransaction();

            $client = new $this->model();
            $client->name = $request->name;
            $client->email = $request->email;
            $client->phone = $request->phone;
            $client->website = $request->website;
            $client->address = $request->address;
            $client->state = $request->state;
            $client->city = $request->city;
            $client->zip = $request->zip;
            $client->country = $request->country_id;
            $client->status_id = $request->status;
            $client->company_id = auth()->user()->company_id;
            $client->date = date('Y-m-d');
            $client->save();

            if ($request->avatar) {
                $this->deleteImage(asset_path($client->avatar_id));
                $client->avatar_id = $this->uploadImage($request->avatar, 'uploads/user')->id;
                $client->save();
            }
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }
    function updateClient($request)
    {
        try {
            DB::beginTransaction();

            $client = $this->getById($request->id);
            $client->name = $request->name;
            $client->email = $request->email;
            $client->phone = $request->phone;
            $client->website = $request->website;
            $client->address = $request->address;
            $client->state = $request->state;
            $client->city = $request->city;
            $client->zip = $request->zip;
            $client->country = $request->country_id;
            $client->status_id = $request->status;
            $client->save();

            if (isset($request->avatar)) {
                $this->deleteImage(asset_path($client->avatar_id));
                $client->avatar_id = $this->uploadImage($request->avatar, 'uploads/user')->id;
                $client->save();
            }
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            return false;
        }
    }

    function dataTable($request)
    {
        $clients = $this->model->query()->where('company_id', auth()->user()->company_id);

        if (@$request->daterange) {
            $dateRange = explode(' - ', $request->daterange);
            $from = date('Y-m-d', strtotime($dateRange[0]));
            $to = date('Y-m-d', strtotime($dateRange[1]));
            $clients = $clients->whereBetween('date', start_end_datetime($from, $to));
        }


        return datatables()->of($clients->latest()->get())
            ->addColumn('name', function ($data) {
                return $data->name;
            })
            ->addColumn('email', function ($data) {
                return $data->email;
            })
            ->addColumn('phone', function ($data) {
                return $data->phone;
            })
            ->addColumn('website', function ($data) {
                return $data->website;
            })

            ->addColumn('file', function ($data) {
                if ($data->avater) {
                    return '<a href="' . uploaded_asset($data->avater->file_id) . '" target="_blank"> <img height="40px" width="40px" src="' . uploaded_asset($data->avater->file_id) . '"/> </a>';
                } else {
                    return '<a href="' . uploaded_asset(0) . '" target="_blank"> <img height="40px" width="40px" src="' . uploaded_asset(0) . '"/> </a>';
                }


            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->addColumn('action', function ($data) {
                $action_button = '';
                $edit = _trans('common.Edit');
                if (hasPermission('client_update')) {
                    $action_button .= '<a href="' . route('client.edit', $data->id) . '" class="dropdown-item"> ' . $edit . '</a>';
                }
                if (hasPermission('client_delete')) {
                    $action_button .= '<a href="' . route('client.delete', $data->id) . '" class="dropdown-item"> ' . _trans('common.Delete') . '</a>';
                }
                $button = '<div class="flex-nowrap">
                <div class="dropdown">
                    <button class="btn btn-white dropdown-toggle align-text-top action-dot-btn" data-boundary="viewport" data-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">' . $action_button . '</div>
                </div>
            </div>';
                return $button;
            })
            ->rawColumns(array('name', 'email', 'phone', 'website', 'file', 'status', 'action'))
            ->make(true);
    }

    function deleteClient($id)
    {
        try {
            DB::beginTransaction();

            $client = $this->getById($id);
            $client->delete();
            Project::where('client_id', $id)->delete();
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }



    // new function for
    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Name'),
            _trans('common.Avatar'),
            _trans('common.Email'),
            _trans('common.Phone'),
            _trans('client.Website'),
            // _trans('common.File'),
            _trans('common.Status'),
            _trans('common.Action')
        ];
    }
    function table($request)
    {
        
        $data = $this->model->query()->where('company_id', auth()->user()->company_id);
        if (@$request->from && @$request->to) {
            $data = $data->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if ($request->search) {
            $data = $data->where('name', 'like', '%' . $request->search . '%');
        }
        $data = $data->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                $delete = _trans('common.Delete');
                if (hasPermission('client_update')) {
                    $action_button .= '<a href="' . route('client.edit', $data->id) . '" class="dropdown-item"> <span class="icon mr-8"><i class="fa-solid fa-pen-to-square"></i></span>' . _trans('common.Edit') . '</a>';
                }
                if (hasPermission('client_delete')) {
                    $action_button .= actionButton($delete, '__globalDelete(' . $data->id . ',`admin/client/delete/`)', 'delete');
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

                                   $user_image='';
                    $user_image .= '<img data-toggle="tooltip" data-placement="top" title="' . $data->name . '" src="' . uploaded_asset($data->avatar_id) . '" class="staff-profile-image-small" >';
                return [
                    'id' => $data->id,
                    'name' => @$data->name,
                    'avatar' => @$user_image,
                    'email' => @$data->email,
                    'phone' => @$data->phone,
                    'website' => @$data->website,
                    // 'file' => '<img height="40px" width="40px" src="' . uploaded_asset(@$data->avater->file_id) . '"/>',
                    'status' => '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>',
                    'action'   => $button
                ];
            }),
            'pagination' => [
                'total' => $data->total(),
                'count' => $data->count(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
                'pagination_html' =>  $data->links('backend.pagination.custom')->toHtml(),
            ],
        ];
    }

      // statusUpdate
      public function statusUpdate($request)
      {
          try {
              
              if (@$request->action == 'active') {
                  $category = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 1]);
                  return $this->responseWithSuccess(_trans('message.Client activate successfully.'), $category);
              }
              if (@$request->action == 'inactive') {
                  $category = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->update(['status_id' => 4]);
                  return $this->responseWithSuccess(_trans('message.Client inactivate successfully.'), $category);
              }
              return $this->responseWithError(_trans('message.Client failed'), [], 400);
          } catch (\Throwable $th) {
              return $this->responseWithError($th->getMessage(), [], 400);
          }
      }
  
  
      public function destroyAll($request)
      {
          try {
              if (@$request->ids) {
                  $category = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                  return $this->responseWithSuccess(_trans('message.Client delete successfully.'), $category);
              } else {
                  return $this->responseWithError(_trans('message.Client not found'), [], 400);
              }
          } catch (\Throwable $th) {
              return $this->responseWithError($th->getMessage(), [], 400);
          }
      }
}
