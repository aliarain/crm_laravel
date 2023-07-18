<?php

namespace App\Repositories\Hrm\Content;

use App\Models\Content\AllContent;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Helpers\CoreApp\Traits\AuthorInfoTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class AllContentRepository
{

    use AuthorInfoTrait, RelationshipTrait, FileHandler;

    protected AllContent $content;
    protected $model;

    public function __construct(AllContent $content, AllContent $model)
    {
        $this->content = $content;
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->query()->where('company_id', 1)->get();
    }

    public function getContent($slug)
    {
        return $this->model->query()->where('company_id', 1)->where('slug', $slug)->get();
    }

    public function index()
    {
    }

    public function store($request): bool
    {
        $this->content->query()->create($request->all());
        return true;
    }

    public function dataTable($request, $id = null)
    {
        $content = $this->content->query()->where('company_id', 1);
        if (@$request->from && @$request->to) {
            $content = $content->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }

        return datatables()->of($content->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                $edit = _trans('common.Edit');
                if (hasPermission('content_update')) {
                    $action_button .= '<a href="' . route('content.edit', $data->id) . '" class="dropdown-item"> ' . $edit . '</a>';
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
            ->addColumn('title', function ($data) {
                return @$data->title;
            })
            ->addColumn('slug', function ($data) {
                return @$data->slug;
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->rawColumns(array('title', 'slug', 'status', 'action'))
            ->make(true);
    }

    public function show($content)
    {
        return $content;
    }

    public function update($request, $content)
    {
        try {
            $content = $this->content->query()->where('id', $content->id)->first();
            $content->title = $request->title;
            $content->content = $request->content;
            $content->status_id = $request->status_id;

            $content->meta_title = $request->meta_title;
            $content->meta_description = $request->meta_description;
            $content->keywords = $request->keywords;
            if ($request->hasFile('meta_image')) {
                $filePath = $this->uploadImage($request->meta_image, 'uploads/frontent');
                @$this->deleteImage(asset_path(@$content->meta_image));
                $content->meta_image = $filePath ? $filePath->id : null;
            }
            $content->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function destroy($content)
    {

        $table_name = $this->model->getTable();
        $foreign_id = \Illuminate\Support\Str::singular($table_name) . '_id';
        return \App\Services\Hrm\DeleteService::deleteData($table_name, $foreign_id, $content->id);
    }

    // new functions

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Title'),
            _trans('common.URL'),
            _trans('common.Status'),
            _trans('common.Action')

        ];
    }

    function table($request)
    {
        
        $data =  $this->model->query();
        $where = array();
        if ($request->search) {
            $where[] = ['title', 'like', '%' . $request->search . '%'];
        }
        $data = $data
            ->where($where)
            ->orderBy('id', 'desc')
            ->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('content_update')) {
                    $action_button .= '<a href="' . route('content.edit', $data->id) . '" class="dropdown-item"> ' . _trans('common.Edit') . '</a>';
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
                    'slug' => $data->slug,
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
}
