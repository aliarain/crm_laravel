<?php

namespace App\Http\Controllers\Backend\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\Setting\MenuRequest;
use App\Repositories\Settings\MenuRepository;

class MenuController extends Controller
{
    protected $menu;
    public function __construct(MenuRepository $menu)
    {
        $this->menu = $menu;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->menu->table($request);
        }
        $data['title'] = _trans('common.Menu List');
        $data['checkbox'] = true;
        $data['table']     = route('menu.index');
        $data['url_id']    = 'menu_table_url';
        $data['fields']    = $this->menu->fields();
        $data['class']     = 'table_class';
        $data['delete_url'] =  route('menu.delete_data');
        $data['status_url'] =  route('menu.statusUpdate');

        return view('backend.settings.menu.index', compact('data'));
    }

    public function create()
    {
        $data['title']    = _trans('common.Create Menu');
        $data['url']      = (hasPermission('menu_store')) ? route('menu.store') : '';
        $data['contents'] = menu();
        return view('backend.settings.menu.create', compact('data'));
    }

    public function store(MenuRequest $request)
    {
        try {
            $result = $this->menu->store($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('menu.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data['edit']     = $this->menu->find($id);
        $data['title']    = _trans('common.Edit Menu');
        $data['url']      = (hasPermission('menu_update')) ? route('menu.update', $id) : '';
        $data['contents'] = menu();
        $data['positions'] = $this->menu->all();
        return view('backend.settings.menu.edit', compact('data'));
    }

    public function update(MenuRequest $request, $id)
    {
        try {
            $result = $this->menu->update($request, $id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('menu.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function delete($id){
        try {
            $result = $this->menu->delete($id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('menu.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    // status change
    public function statusUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        return $this->menu->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->menu->destroyAll($request);
    }
}
