<?php

namespace App\Http\Controllers\Leads;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\Leads\SourceRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class SourceController extends Controller
{
    use ApiReturnFormatTrait;
    protected $sourceRepo;

    public function __construct(
        SourceRepository $sourceRepo
        )
    {
        $this->sourceRepo = $sourceRepo;
    } 
    public function index(Request $request){

        $data = [
            'fields' => $this->sourceRepo->fields(), // Get the fields for the sources table
            // Set the properties for the view data
            'checkbox' => true, // Show the checkbox for the table
            'class' => 'sources_datatable', // Class for the table
            'status_url' => route('source.statusUpdate'),
            'delete_url' => route('source.deleteData'),
            'title' => _trans('source.Source List'),
        ];

        if ($request->ajax()) {
            return $this->sourceRepo->table($request);
        }
        // Return the view with the data
        return view('backend.leads.source.index', compact('data'));
    }

    public function create()
    {
        try {
            $data['title'] = _trans('lead.Source Create');
            $data['url'] = (hasPermission('source_store')) ? route('source.store') : '';
            return view('backend.leads.source.create', compact('data'));
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
            $result = $this->sourceRepo->storeSource($request);
            if ($result) {
                Toastr::success(_trans('response.Source created successfully'), 'Created');
                return redirect()->route('source.index');
            } else {
                Toastr::error(_trans('response.Something went wrong!'), 'Error');
                return redirect()->route('source.index');
            }
        } catch (\Throwable$th) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->route('source.index');
        }
    }

    public function edit($id)
    {
        $data['title'] = _trans('lead.Edit Source Information');
        $data['show'] = $this->sourceRepo->getSourceById($id);
        return view('backend.leads.source.create', compact('data'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        $result = $this->sourceRepo->updateSource($request);
        if ($result) {
            Toastr::success(_trans('response.Source updated successfully'), 'Updated');
            return redirect()->route('source.index');
        }

        Toastr::error(_trans('response.Something went wrong!'), 'Error');
        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            $result = $this->sourceRepo->deleteSource($id);
            if ($result) {
                Toastr::success(_trans('response.Source deleted successfully'), 'Deleted');
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
