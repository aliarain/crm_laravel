<?php
namespace Modules\Sale\Repositories;
use Modules\Sale\Entities\SaleCategory;

class SaleCategoryRepository
{
    protected $category;

    public function __construct(SaleCategory $category)
    {
        $this->category = $category;
    }

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Name'),
            _trans('common.Image'),
            _trans('common.Parent'),
            _trans('common.Status'),
            _trans('common.Action')
        ];
    }

    function table($request){
        $data = $this->category->query();
    
        if ($request->search) {
            $data = $data->where('name', 'like', '%' . $request->search . '%');
        }
        $data = $data->orderBy('id', 'desc')->paginate(10);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('support_delete')) {
                    $action_button .= actionButton('Delete', '__globalDelete(' . $data->id . ',`hrm/support/ticket/delete/`)', 'delete');
                }

                return [
                    'id'        => $data->id,
                    'name'      => @$data->name,
                    'image'     => @$data->image,
                    'parent'    => @$data->parent,
                    'status'    => '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>',
                    'action'    => actionHTML($action_button)
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
