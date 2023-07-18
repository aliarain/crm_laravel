<?php

namespace App\Http\Controllers\Backend\Company;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Settings\locationRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class LocationController extends Controller
{
    use ApiReturnFormatTrait;
    protected $locationRepository;

    public function __construct(locationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    function location(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $this->locationRepository->table($request);
            }
            $data['table']    = route('company.settings.location');
            $data['url_id']    = 'location_table_url';
            $data['class']     = 'table_class';
            $data['delete_url'] =  route('location.delete_data');
            $data['status_url'] =  route('location.statusUpdate');
            $data['checkbox'] = true;
            $data['fields']           = $this->locationRepository->fields();

            $data['title']            = _trans('settings.Location Binding');
            return view('backend.company_setup.location.index', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    function datatable()
    {
        return $this->locationRepository->datatable();
    }

    function locationCreate()
    {
        $data['title']            = _trans('settings.Location Binding Create');
        $data['url']          = (hasPermission('location_create')) ? route('company.settings.locationStore') : '';
        return view('backend.company_setup.location.create', compact('data'));
    }

    function locationPicker(Request $request)
    {
        try {
            return view('backend.company_setup.location.location_picker_modal');
        } catch (\Throwable $e) {
            return response()->json('fail');
        }
    }

    function locationStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'distance' => 'required',
                'status' => 'required',
                'location' => 'required',
            ]
        );

        if ($validator->fails()) {
            return $this->responseWithError(_trans('common.Required field missing'), $validator->errors(), 422);
        }
        try {
            $result = $this->locationRepository->create($request);
            if (@$result->original['result']) {
                return $this->responseWithSuccess($result->original['message'], route('company.settings.location'));
            } else {
                return $this->responseWithError($result->original['message'], 422);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $data['location']  = $this->locationRepository->model([
                'id' => $id,
                'company_id' => auth()->user()->company_id,
            ])->first();
            $data['title']        =  _trans('settings.Edit Location Binding');
            $data['url']          = (hasPermission('location_update')) ? route('company.settings.locationUpdate', $id) : '';
            return view('backend.company_setup.location.edit', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    function locationUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'distance' => 'required',
                'status' => 'required',
                'location' => 'required',
            ]
        );

        if ($validator->fails()) {
            return $this->responseWithError(_trans('common.Required field missing'), $validator->errors(), 422);
        }
        try {
            $result = $this->locationRepository->update($request, $id);
            if (@$result->original['result']) {
                return $this->responseWithSuccess($result->original['message'], route('company.settings.location'));
            } else {
                return $this->responseWithError($result->original['message'], 422);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage());
        }
    }

    public function locationDestroy($id)
    {
        try {
            $result = $this->locationRepository->delete($id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('company.settings.location');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->route('company.settings.location');
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->route('company.settings.location');
        }
    }

    // status change
    public function statusUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        return $this->locationRepository->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->locationRepository->destroyAll($request);
    }
}
