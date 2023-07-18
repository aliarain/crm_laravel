<?php

namespace App\Repositories\Settings;

use function route;
use function datatables;
use function actionButton;
use App\Models\Frontend\Menu;
use function start_end_datetime;
use Illuminate\Support\Facades\Log;
use App\Models\Traits\RelationCheck;
use Brian2694\Toastr\Facades\Toastr;
use App\Helpers\CoreApp\Traits\AuthorInfoTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class MenuRepository
{
    use AuthorInfoTrait, RelationshipTrait, RelationCheck, ApiReturnFormatTrait;

    protected $menu;

    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Name'),
            _trans('common.Type'),
            _trans('common.Status'),
            _trans('common.Action')

        ];
    }

    function find($id)
    {
        return $this->menu->find($id);
    }
    function all()
    {
        return $this->menu->orderBy('position', 'ASC')->get();
    }

    function table($request)
    {
        $data =  $this->menu->query()->with('status');
        $where = array();
        if ($request->search) {
            $where[] = ['name', 'like', '%' . $request->search . '%'];
        }
        if ($request->from && $request->to) {
            $data = $data->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        $data = $data
            ->where($where)
            ->orderBy('position', 'asc')
            ->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('menu_edit')) {
                    $action_button .= '<a href="' . route('menu.edit', $data->id) . '" class="dropdown-item"> ' . _trans('common.Edit') . '</a>';
                }
                if (hasPermission('menu_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__deleteAlert(`' . route('menu.delete', $data->id) . '`)', 'delete');
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
                    'type' => @$data->type == 1 ? _trans('common.Header') : _trans('common.Footer'),
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
            $menu                           = new $this->menu;
            $menu->name                     = $request->name;
            $menu->position                 = $this->menu->max('position') + 1;
            $menu->url                      = @$request->url;
            $menu->all_content_id           = @$request->url ?? $request->page_id;
            $menu->type                     = $request->type;
            $menu->status_id                = $request->status;
            $menu->save();
            return $this->responseWithSuccess(_trans('message.Menu created successfully.'), $menu);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function update($request, $id)
    {
        try {
            $menu = $this->menu->where(['id' => $id])->first();
            if (!$menu) {
                return $this->responseWithError(_trans('message.Menu not found'), 'id', 404);
            }
            $menu->name                     = $request->name;
            $menu->position                 = $request->position;
            $menu->url                      = @$request->url;
            $menu->all_content_id           = @$request->url ?? $request->page_id;
            $menu->type                     = $request->type;
            $menu->status_id                = $request->status;
            $menu->save();
            return $this->responseWithSuccess(_trans('message.Menu Updated successfully.'), $menu);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    // status Update
    public function statusUpdate($request)
    {
        try {
            if (@$request->action == 'active') {
                $menu = $this->menu->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Menu activate successfully.'), $menu);
            }
            if (@$request->action == 'inactive') {
                $menu = $this->menu->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Menu inactivate successfully.'), $menu);
            }
            return $this->responseWithError(_trans('message.Menu failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function delete($id)
    {
        $menu = $this->menu->where(['id' => $id])->first();
        if (!$menu) {
            return $this->responseWithError(_trans('message.Menu not found'), 'id', 404);
        }
        try {
            $menu->delete();
            return $this->responseWithSuccess(_trans('message.Menu Delete successfully.'), $menu);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $menu = $this->menu->whereIn('id', $request->ids)->delete();
                return $this->responseWithSuccess(_trans('message.Menu delete successfully.'), $menu);
            } else {
                return $this->responseWithError(_trans('message.Menu not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
