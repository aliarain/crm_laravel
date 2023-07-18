<?php

namespace App\Http\Controllers\Backend\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\Hrm\Client\ClientRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use Validator;

class ClientController extends Controller
{
    use ApiReturnFormatTrait;
    protected $clientRepo;

    public function __construct(ClientRepository $clientRepo)
    {
        $this->clientRepo = $clientRepo;
    }


    public function index(Request $request)
    {
        $data['fields']     = $this->clientRepo->fields();
        if ($request->ajax()) {
            return $this->clientRepo->table($request);
        }
        $data['checkbox'] = true;
        $data['class'] = 'clients_datatable';
        $data['status_url'] = route('hrm.client.statusUpdate');
        $data['delete_url'] = route('hrm.client.deleteData');

        $data['title'] = _trans('client.Client List');

        return view('backend.client.index', compact('data'));
    }
    public function create()
    {

        $data['title'] = _trans('client.Add New Client');

        return view('backend.client.create', compact('data'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email'=>'sometimes|nullable|email|unique:clients',
            'phone'=>'sometimes|nullable|unique:clients',
        ]);
        try {
            $result = $this->clientRepo->storeClient($request);
            if ($result) {
                Toastr::success(_trans('response.Client created successfully'), 'Created');
                return redirect()->route('client.index');
            } else {
                Toastr::error(_trans('response.Something went wrong!'), 'Error');
                return redirect()->route('client.create');
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->route('client.create');
        }
    }
    public function update(Request $request)
    {

        $rules = [
            'id' => 'required',
            'name' => 'required',
            'email' => 'sometimes|nullable|email|unique:clients,email,' . $request->id,
            'phone' => 'sometimes|nullable|numeric|unique:clients,phone,' . $request->id,
        ];

        $message = [
            'phone.numeric' => _trans('message.Phone number should be numeric')
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $result = $this->clientRepo->updateClient($request);
        if ($result) {
            Toastr::success(_trans('response.Client updated successfully'), 'Updated');
            return redirect()->route('client.index');
        }

        Toastr::error(_trans('response.Something went wrong!'), 'Error');
        return redirect()->back();
    }

    public function datatable(Request $request)
    {
        return $this->clientRepo->dataTable($request);
    }

    public function edit($id)
    {
        $data['title'] = _trans('client.Edit Client');
        $data['show'] = $this->clientRepo->getById($id);
        return view('backend.client.create', compact('data'));
    }
    public function delete($id)
    {
        try {
            $result = $this->clientRepo->deleteClient($id);
            if ($result) {
                Toastr::success(_trans('response.Client deleted successfully'), 'Deleted');
                return redirect()->route('client.index');
            } else {
                Toastr::error(_trans('response.Something went wrong!'), 'Error');
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
        return $this->clientRepo->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->clientRepo->destroyAll($request);
    }
}
