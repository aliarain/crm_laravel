<?php

namespace App\Http\Controllers\coreApp\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\coreApp\Setting\IpSetup;
use App\Repositories\Settings\IpRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Http\Requests\Configuration\IPAddressReqeust;

class IpConfigController extends Controller
{
    use ApiReturnFormatTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $ipConfig;

    public function __construct(IpRepository $ipConfig)
    {
        $this->ipConfig = $ipConfig;
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $this->ipConfig->table($request);
            }
            $data['table']    = route('ipConfig.index');
            $data['url_id']    = 'ip_table_url';
            $data['class']     = 'table_class';
            $data['delete_url'] =  route('ipConfig.delete_data');
            $data['status_url'] =  route('ipConfig.statusUpdate');
            $data['checkbox'] = true;
            $data['fields'] = $this->ipConfig->fields();

            $data['title'] = _trans('settings.IP Whitelist');
            return view('backend.ip_setting.index', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $data['title']     = _trans('common.Create IP Address');
            $data['url']       = route('ipConfig.store');
            $data['attributes'] = $this->ipConfig->createAttributes();
            @$data['button']   = _trans('common.Save');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(IPAddressReqeust $request)
    {
        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->ipConfig->newStore($request);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    public function datatable()
    {
        return $this->ipConfig->dataTable();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $ipConfigModel = $this->ipConfig->model()->find($id);
            $data['title'] = _trans('common.Edit IP Address');
            $data['url']          = route('ipConfig.update', $ipConfigModel->id);
            $data['attributes'] = $this->ipConfig->editAttributes($ipConfigModel);
            @$data['button']   = _trans('common.Update');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(IPAddressReqeust $request, $id)
    {
        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->ipConfig->newUpdate($request, $id);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        try {
            $delete = $this->ipConfig->deleteIp($id);
            if ($delete) {
                Toastr::success('IP Address Deleted Successfully', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('IP Address Not Deleted', 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    // status change
    public function statusUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        return $this->ipConfig->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->ipConfig->destroyAll($request);
    }
}
