<?php

namespace Modules\Sale\Http\Controllers;

use Modules\Sale\Repositories\SaleCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class SaleCategoryController extends Controller
{

    use ApiReturnFormatTrait;

    protected $category;

    public function __construct(SaleCategoryRepository $categoryRepo)
    {
        $this->category = $categoryRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->category->table($request);
        }
        $data['fields']        = $this->category->fields();
        $data['checkbox']      = true;
        $data['delete_url']    = route('supportTicket.delete_data');
        $data['table']         = route('saleProductCategory.index');
        $data['url_id']        = 'productCategory';
        $data['class']         = 'productCategory';


        $data['title'] = _trans('support.Category List');
        $data['id'] = auth()->id();
        return view('sale::product.category.index', compact('data'));
    }


}
