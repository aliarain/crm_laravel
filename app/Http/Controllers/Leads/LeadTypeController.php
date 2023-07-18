<?php

namespace App\Http\Controllers\Leads;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\Leads\LeadRepository;
use App\Repositories\Leads\LeadTypeRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class LeadTypeController extends Controller
{
    use ApiReturnFormatTrait;
    protected $leadTypeRepo;

    public function __construct(
        LeadTypeRepository $leadTypeRepo
    ) {
        $this->leadTypeRepo = $leadTypeRepo;
    }
    public function index(Request $request)
    {
        $data = [
            'fields' => $this->leadTypeRepo->fields(), // Get the fields for the leads table
            // Set the properties for the view data
            'checkbox' => true, // Show the checkbox for the table
            'class' => 'types_datatable', // Class for the table
            'status_url' => route('type.statusUpdate'),
            'delete_url' => route('type.deleteData'),
            'title' => _trans('type.Lead Type List'),
        ]; 
        if ($request->ajax()) {
            return response()->json($this->leadTypeRepo->table($request));
        }
        // Return the view with the data
        return view('backend.leads.type.index', compact('data'));
    }

    public function create()
    {
        try {
            $data['title'] = _trans('lead.Lead Type Create');
            $data['url'] = (hasPermission('lead_store')) ? route('type.store') : '';
            return view('backend.leads.type.create', compact('data'));
        } catch (\Throwable$th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);
        try {
            $result = $this->leadTypeRepo->storeType($request);
            if ($result) {
                Toastr::success(_trans('response.Lead Type created successfully'), 'Created');
                return redirect()->route('type.index');
            } else {
                Toastr::error(_trans('response.Something went wrong!'), 'Error');
                return redirect()->route('type.index');
            }
        } catch (\Throwable$th) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->route('type.index');
        }
    }

    public function edit($id)
    {
        $data['title'] = _trans('lead.Edit Lead Type Information');
        $data['show'] = $this->leadTypeRepo->getById($id);
        return view('backend.leads.type.create', compact('data'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        $result = $this->leadTypeRepo->updateType($request);
        if ($result) {
            Toastr::success(_trans('response.Type updated successfully'), 'Updated');
            return redirect()->route('type.index');
        }

        Toastr::error(_trans('response.Something went wrong!'), 'Error');
        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            $result = $this->leadTypeRepo->deleteType($id);
            if ($result) {
                Toastr::success(_trans('response.Lead Type deleted successfully'), 'Deleted');
                return redirect()->route('type.index');
            } else {
                Toastr::error(_trans('response.Something went wrong!'), 'Error');
                return redirect()->back();
            }
        } catch (\Throwable$th) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
}
