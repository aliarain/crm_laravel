<?php

namespace App\Http\Controllers\Leads;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\Leads\LeadStatusRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class LeadStatusController extends Controller
{
    use ApiReturnFormatTrait;
    protected $leadStatusRepo;

    public function __construct(
        LeadStatusRepository $leadStatusRepo
    ) {
        $this->leadStatusRepo = $leadStatusRepo;
    }
    public function index(Request $request)
    {
        $data = [
            'fields' => $this->leadStatusRepo->fields(), // Get the fields for the leads table
            // Set the properties for the view data
            'checkbox' => true, // Show the checkbox for the table
            'class' => 'statuses_datatable', // Class for the table
            'status_url' => route('type.statusUpdate'),
            'delete_url' => route('type.deleteData'),
            'title' => _trans('type.Lead Status List'),
        ]; 
        if ($request->ajax()) {
            return response()->json($this->leadStatusRepo->table($request));
        }
        // Return the view with the data
        return view('backend.leads.status.index', compact('data'));
    }


    public function create()
    {
        try {
            $data['title'] = _trans('lead.Lead Status Create');
            $data['url'] = (hasPermission('lead_store')) ? route('status.store') : '';
            return view('backend.leads.status.create', compact('data'));
        } catch (\Throwable$th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'border_color' => 'required',
            'background_color' => 'required',
            'text_color' => 'required',
        ]);
        try {
            $result = $this->leadStatusRepo->storeStatus($request);
            if ($result) {
                Toastr::success(_trans('response.Lead Status created successfully'), 'Created');
                return redirect()->route('status.index');
            } else {
                Toastr::error(_trans('response.Something went wrong!'), 'Error');
                return redirect()->route('status.index');
            }
        } catch (\Throwable$th) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->route('status.index');
        }
    }

    public function edit($id)
    {
        $data['title'] = _trans('Edit Lead Status Information');
        $data['show'] = $this->leadStatusRepo->getStatusById($id);
        return view('backend.leads.status.create', compact('data'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'border_color' => 'required',
            'background_color' => 'required',
            'text_color' => 'required',
        ]);

        $result = $this->leadStatusRepo->updateStatus($request);
        if ($result) {
            Toastr::success(_trans('response.Status updated successfully'), 'Updated');
            return redirect()->route('status.index');
        }

        Toastr::error(_trans('response.Something went wrong!'), 'Error');
        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            $result = $this->leadStatusRepo->deleteStatus($id);
            if ($result) {
                Toastr::success(_trans('response.Lead Status deleted successfully'), 'Deleted');
                return redirect()->route('status.index');
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
