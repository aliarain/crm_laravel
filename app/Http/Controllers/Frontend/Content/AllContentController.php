<?php

namespace App\Http\Controllers\Frontend\Content;

use App\Models\Content\AllContent;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Helpers\CoreApp\Traits\AuthorInfoTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Content\AllContentRepository;
use Illuminate\Http\Request;

class AllContentController extends Controller
{


    use AuthorInfoTrait, RelationshipTrait;

    protected AllContentRepository $content;
    protected $model;

    public function __construct(AllContentRepository $content, AllContent $model)
    {
        $this->content = $content;
        $this->model = $model;
    }


    public function list(Request $request)
    {
        if ($request->ajax()) {
            return $this->content->table($request);
        }
        $data['fields'] = $this->content->fields();
        $data['title'] = _trans('common.Languages');
        $data['url_id']    = 'content_table_url';
        $data['class']     = 'table_class';


        $data['title'] = _trans('common.All Content');
        return view('backend.content.list', compact('data'));
    }

    public function create()
    {

        $data['title'] = _trans('common.Add Content');
        return view('backend.content.create', compact('data'));
    }


    public function edit(AllContent $allContent)
    {
        $data['title'] = _trans('common.Edit content');
        $data['show'] = $allContent;
        return view('backend.content.edit', compact('data'));
    }


    public function update(Request $request, AllContent $allContent)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        try {
            $this->content->update($request, $allContent);
            Toastr::success(_trans('response.Operation successful'), 'Success');
            return redirect()->route('content.list');
        } catch (\Throwable $throwable) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function dataTable(Request $request)
    {
        return $this->content->dataTable($request);
    }


    public function delete(AllContent $allContent): \Illuminate\Http\RedirectResponse
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        try {
            $this->content->destroy($allContent);
            Toastr::success(_trans('common.Operation successful'), 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }
}
