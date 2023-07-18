<?php

namespace App\Repositories\Settings;

use function route;
use function actionButton;
use Illuminate\Support\Str;
use function start_end_datetime;
use App\Models\Frontend\FrontTeam;
use Illuminate\Support\Facades\Log;
use App\Models\Traits\RelationCheck;
use Brian2694\Toastr\Facades\Toastr;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Helpers\CoreApp\Traits\AuthorInfoTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class FrontTeamRepository
{
    use AuthorInfoTrait, RelationshipTrait, RelationCheck, ApiReturnFormatTrait, FileHandler;

    protected $service;

    public function __construct(FrontTeam $service)
    {
        $this->service = $service;
    }

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Name'),
            _trans('common.Designation'),
            _trans('common.attachment'),
            _trans('common.Status'),
            _trans('common.Action')

        ];
    }

    function find($id)
    {
        return $this->service->find($id);
    }
    function all()
    {
        return $this->service->orderBy('position', 'ASC')->get();
    }

    function table($request)
    {
        
        $data =  $this->service->query()->with('status');
        $where = array();
        if ($request->search) {
            $where[] = ['name', 'like', '%' . $request->search . '%'];
        }
        if ($request->from && $request->to) {
            $data = $data->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        $data = $data
            ->where($where)
            ->orderBy('id', 'desc')
            ->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('team_member_edit')) {
                    $action_button .= '<a href="' . route('team-member.edit', $data->id) . '" class="dropdown-item"> ' . _trans('common.Edit') . '</a>';
                }
                if (hasPermission('team_member_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__deleteAlert(`' . route('team-member.delete', $data->id) . '`)', 'delete');
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
                    'name' => $data->name,
                    'designation' => $data->designation,
                    'attachment' => '<img src="' . uploaded_asset($data->attachment) . '" alt="" width="50px" height="50px">',
                    'status' => '<small class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</small>',
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

    function store($request)
    {
        try {
            $service                           = new $this->service;
            $service->name                     = $request->name;
            $service->designation              = $request->designation;
            $service->description              = $request->content;
            $service->status_id               = $request->status;
            $service->user_id               = auth()->user()->id;
            if ($request->hasFile('attachment')) {
                $service->attachment = $this->uploadImage($request->attachment, 'team_member/files')->id;
            }
            $service->save();
            return $this->responseWithSuccess(_trans('message.Team member created successfully.'), $service);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function update($request, $id)
    {
        try {
            $service = $this->service->where(['id' => $id])->first();
            if (!$service) {
                return $this->responseWithError(_trans('message.Team member not found'), 'id', 404);
            }
            $service->name                     = $request->name;
            $service->designation              = $request->designation;
            $service->description              = $request->content;
            $service->status_id               = $request->status;
            $service->user_id               = auth()->user()->id;
            if ($request->hasFile('attachment')) {
                if (@$service->attachment) {
                    $this->deleteImage(asset_path($service->attachment));
                }
                $service->attachment = $this->uploadImage($request->attachment, 'team_member/files')->id;
            }
            $service->save();
            return $this->responseWithSuccess(_trans('message.Team member Updated successfully.'), $service);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    // statusUpdate
    public function statusUpdate($request)
    {
        try {
            
            if (@$request->action == 'active') {
                $menu = $this->service->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Team member activate successfully.'), $menu);
            }
            if (@$request->action == 'inactive') {
                $menu = $this->service->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Team member inactivate successfully.'), $menu);
            }
            return $this->responseWithError(_trans('message.Team member failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function delete($id)
    {
        $menu = $this->service->where(['id' => $id])->first();
        if (!$menu) {
            return $this->responseWithError(_trans('message.Team member not found'), 'id', 404);
        }
        try {
            if (@$menu->attachment) {
                $this->deleteImage(asset_path($menu->attachment));
            }
            $menu->delete();
            return $this->responseWithSuccess(_trans('message.Team member Delete successfully.'), $menu);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $menus = $this->service->whereIn('id', $request->ids)->get();
                foreach ($menus as $menu) {
                    if (@$menu->attachment) {
                        $this->deleteImage(asset_path($menu->attachment));
                    }
                    $menu->delete();
                }
                return $this->responseWithSuccess(_trans('message.Team member delete successfully.'), $menu);
            } else {
                return $this->responseWithError(_trans('message.Team member not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
