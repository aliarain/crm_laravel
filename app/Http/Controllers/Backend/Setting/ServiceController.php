<?php

namespace App\Http\Controllers\Backend\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\Setting\ServiceRequest;
use App\Repositories\Settings\ServiceRepository;

class ServiceController extends Controller
{
    protected $service;
    public function __construct(ServiceRepository $service)
    {
        $this->service = $service;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->service->table($request);
        }
        $data['title'] = _trans('common.Service List');
        $data['checkbox'] = true;
        $data['table']     = route('service.index');
        $data['url_id']    = 'service_table_url';
        $data['fields']    = $this->service->fields();
        $data['class']     = 'table_class';
        $data['delete_url'] =  route('service.delete_data');
        $data['status_url'] =  route('service.statusUpdate');

        return view('backend.settings.service.index', compact('data'));
    }

    public function create()
    {
        $data['title']    = _trans('common.Create Service');
        $data['url']      = (hasPermission('service_store')) ? route('service.store') : '';
        $data['button']   = _trans('common.Save');
        return view('backend.settings.service.create', compact('data'));
    }

    public function store(ServiceRequest $request)
    {
        try {
            $result = $this->service->store($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('service.index');
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
        $data['edit']     = $this->service->find($id);
        $data['title']    = _trans('common.Edit Service');
        $data['url']      = (hasPermission('service_update')) ? route('service.update', $id) : '';
        $data['button']   = _trans('common.Update');
        return view('backend.settings.service.edit', compact('data'));
    }

    public function update(ServiceRequest $request, $id)
    {
        try {
            $result = $this->service->update($request, $id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('service.index');
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
            $result = $this->service->delete($id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('service.index');
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
        return $this->service->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->service->destroyAll($request);
    }
}
