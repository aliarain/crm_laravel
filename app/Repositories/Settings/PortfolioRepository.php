<?php

namespace App\Repositories\Settings;

use function route;
use function datatables;
use function actionButton;
use Illuminate\Support\Str;
use App\Models\Frontend\Menu;
use App\Models\Frontend\Service;
use function start_end_datetime;
use App\Models\Frontend\Portfolio;
use Illuminate\Support\Facades\Log;
use App\Models\Traits\RelationCheck;
use Brian2694\Toastr\Facades\Toastr;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Helpers\CoreApp\Traits\AuthorInfoTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class PortfolioRepository
{
    use AuthorInfoTrait, RelationshipTrait, RelationCheck, ApiReturnFormatTrait,FileHandler;

    protected $service;

    public function __construct(Portfolio $service)
    {
        $this->service = $service;
    }

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Title'),
            _trans('common.Attachment'),
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
            $where[] = ['title', 'like', '%' . $request->search . '%'];
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
                if (hasPermission('portfolio_edit')) {
                    $action_button .= '<a href="' . route('portfolio.edit', $data->id) . '" class="dropdown-item"> ' . _trans('common.Edit') . '</a>';
                }
                if (hasPermission('portfolio_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__deleteAlert(`' . route('portfolio.delete', $data->id) . '`)', 'delete');
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
                    'title' => $data->title,
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
            $service->title                     = $request->title;
            $service->slug                     = Str::slug($request->title);
            $service->description              = $request->content;
            $service->status_id               = $request->status;
            $service->user_id               = auth()->user()->id;
            if ($request->hasFile('attachment')) {
                $service->attachment = $this->uploadImage($request->attachment, 'portfolio/files')->id;
            }
            $service->save();
            return $this->responseWithSuccess(_trans('message.Portfolio created successfully.'), $service);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function update($request, $id)
    {
        try {
            $service = $this->service->where(['id' => $id])->first();
            if (!$service) {
                return $this->responseWithError(_trans('message.Portfolio not found'), 'id', 404);
            }
            $service->title                     = $request->title;
            $service->slug                     = Str::slug($request->title);
            $service->description              = $request->content;
            $service->status_id               = $request->status;
            if ($request->hasFile('attachment')) {
                if (@$service->attachment) {
                    $this->deleteImage(asset_path($service->attachment));
                }
                $service->attachment = $this->uploadImage($request->attachment, 'portfolio/files')->id;
            }
            $service->save();
            return $this->responseWithSuccess(_trans('message.Portfolio Updated successfully.'), $service);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    // status Update
    public function statusUpdate($request)
    {
        try {
            if (@$request->action == 'active') {
                $menu = $this->service->whereIn('id', $request->ids)->update(['status_id' => 1]);
                return $this->responseWithSuccess(_trans('message.Portfolio activate successfully.'), $menu);
            }
            if (@$request->action == 'inactive') {
                $menu = $this->service->whereIn('id', $request->ids)->update(['status_id' => 4]);
                return $this->responseWithSuccess(_trans('message.Portfolio inactivate successfully.'), $menu);
            }
            return $this->responseWithError(_trans('message.Portfolio failed'), [], 400);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function delete($id)
    {
        $menu = $this->service->where(['id' => $id])->first();
        if (!$menu) {
            return $this->responseWithError(_trans('message.Portfolio not found'), 'id', 404);
        }
        try {
            $menu->delete();
            return $this->responseWithSuccess(_trans('message.Portfolio Delete successfully.'), $menu);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $menu = $this->service->whereIn('id', $request->ids)->delete();
                return $this->responseWithSuccess(_trans('message.Service delete successfully.'), $menu);
            } else {
                return $this->responseWithError(_trans('message.Service not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
