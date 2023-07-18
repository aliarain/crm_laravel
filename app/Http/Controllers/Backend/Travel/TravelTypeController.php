<?php

namespace App\Http\Controllers\Backend\Travel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Services\Travel\TravelTypeService;
use App\Http\Requests\TravelTypeStoreRequest;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class TravelTypeController extends Controller
{
    use ApiReturnFormatTrait;

    protected $service;

    public function __construct(TravelTypeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $data['checkbox'] = true;
            $data['title']     = _trans('travel.Travel Type List');
            $data['table']     = route('travel_type.table');
            $data['url_id']    = 'travel_type_table_url';
            $data['fields']    = $this->service->fields();
            $data['class']     = 'travel_type_table_class';
            return view('backend.travel.type.index', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function table(Request $request)
    {
        if ($request->ajax()) {
            return $this->service->table($request);
        }
    }

    public function create()
    {
        try {
            $data['title']     = _trans('travel.Create Travel Type');
            $data['url']       = (hasPermission('travel_type_store')) ? route('travel_type.store') : '';
            @$data['button']   = _trans('common.Save');
            return view('backend.travel.type.createModal', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }
    public function store(TravelTypeStoreRequest $request)
    {
       
        try {
            $result = $this->service->store($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('travel_type.index');
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
        try {
            $data['edit']      = $this->service->where([
                'id' => $id,
                'company_id' => auth()->user()->company_id
            ])->first();
            if (blank($data['edit'])) {
                Toastr::error(_trans('validation.response.Data not found!'), 'Error');
                return redirect()->back();
            }
            $data['title']     = _trans('travel.Update Travel Type');
            $data['url']       = (hasPermission('travel_type_update')) ? route('travel_type.update', $data['edit']->id) : '';
            $data['button']   = _trans('common.Update');
            return view('backend.travel.type.createModal', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function update(TravelTypeStoreRequest $request, $id)
    {
        try {
            $result = $this->service->update($request, $id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('travel_type.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->service->delete($id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('travel_type.index');
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
