<?php

namespace App\Repositories\Hrm\Contact;

use App\Models\Hrm\Contact;
use App\Helpers\CoreApp\Traits\AuthorInfoTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class ContactRepository
{

    use AuthorInfoTrait, RelationshipTrait;

    protected $model;

    public function __construct(Contact $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->query()->get();
    }
    public function dataTable($request)
    {
        $content = $this->model->query();
        if (@$request->from && @$request->to) {
            $content = $content->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }

        return datatables()->of($content->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                if (hasPermission('leave_type_update')) {
                    $action_button .= '<a href="' . route('content.edit', $data->id) . '" class="dropdown-item"> Edit</a>';
                }
                if (hasPermission('leave_type_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`admin/settings/content/delete/`)', 'delete');
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

    //new functions for
    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Name'),
            _trans('common.Email'),
            _trans('common.Phone'),
            _translate('Contact For'),
            _translate('Message'),
            // _trans('common.Status'),

        ];
    }

    function table($request)
    {
        
        $data =  $this->model->query();
        $where = array();
        if ($request->search) {
            $where[] = ['name', 'like', '%' . $request->search . '%'];
        }
        $data = $data
            ->where($where)
            ->orderBy('id', 'desc')
            ->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {

                return [
                    'id' => $data->id,
                    'name' => $data->name ,
                    'email' => $data->email,
                    'phone' => $data->phone,
                    'contact_for' => $data->contact_for,
                    'message' => $data->message,
                    'contact_status' => $data->IsActive
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
