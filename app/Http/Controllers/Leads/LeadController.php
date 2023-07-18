<?php

namespace App\Http\Controllers\Leads;

use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Http\Controllers\Controller;
use App\Mail\Lead\MyTestMail;
use App\Models\Leads\Lead;
use App\Models\User;
use App\Repositories\Leads\LeadRepository;
use App\Repositories\Leads\SourceRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Validator;

class LeadController extends Controller
{
    use ApiReturnFormatTrait, FileHandler;
    protected $leadRepo, $sourceRepo;

    public function __construct(
        LeadRepository $leadRepo,
        SourceRepository $sourceRepo
    ) {
        $this->leadRepo = $leadRepo;
        $this->sourceRepo = $sourceRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = [
            'fields' => $this->leadRepo->fields(), // Get the fields for the leads table
            // Set the properties for the view data
            'checkbox' => true, // Show the checkbox for the table
            'class' => 'leads_datatable', // Class for the table
            'status_url' => route('lead.statusUpdate'),
            'delete_url' => route('lead.deleteData'),
            'title' => _trans('lead.Lead List'),
        ];
        $data['lead_types'] = DB::table('lead_types')->where('company_id', auth()->user()->company_id)->orderBy('title', 'ASC')->select('id', 'title')->get();
        $data['lead_sources'] = DB::table('lead_sources')->where('company_id', auth()->user()->company_id)->orderBy('title', 'ASC')->select('id', 'title')->get();
        $data['lead_statuses'] = DB::table('lead_statuses')->where('company_id', auth()->user()->company_id)->orderBy('title', 'ASC')->select('id', 'title')->get();

        if ($request->ajax()) {
            return response()->json($this->leadRepo->table($request));

            // return $this->leadRepo->table($request);
        }
        // Return the view with the data
        return view('backend.leads.index', compact('data'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'title' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country_id' => 'required',
            'zip' => 'required',
            'address' => 'required',
            'lead_type_id' => 'required',
            'lead_source_id' => 'required',
            'lead_status_id' => 'required',
        ]);
        try {
            $result = $this->leadRepo->storeLead($request);
            if ($result) {
                Toastr::success(_trans('response.Lead created successfully'), 'Created');
                return redirect()->route('lead.index');
            } else {
                Toastr::error(_trans('response.Something went wrong!'), 'Error');
                return redirect()->route('lead.create');
            }
        } catch (\Throwable$th) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->route('lead.create');
        }
    }
    public function update(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'title' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country_id' => 'required',
            'zip' => 'required',
            'address' => 'required',
            'lead_type_id' => 'required',
            'lead_source_id' => 'required',
            'lead_status_id' => 'required',
        ]);

        $result = $this->leadRepo->updateLead($request);
        if ($result) {
            Toastr::success(_trans('response.Lead updated successfully'), 'Updated');
            return redirect()->route('lead.index');
        }

        Toastr::error(_trans('response.Something went wrong!'), 'Error');
        return redirect()->back();
    }

    //datatable data fetch
    public function datatable(Request $request)
    {
        return $this->leadRepo->dataTable($request);
    }

    public function edit($id)
    {
        $data['title'] = _trans('client.Edit Client Information');
        $data['show'] = $this->leadRepo->getLeadById($id);
        $data['lead_types'] = DB::table('lead_types')->where('company_id', auth()->user()->company_id)->orderBy('title', 'ASC')->select('id', 'title')->get();
        $data['lead_sources'] = DB::table('lead_sources')->where('company_id', auth()->user()->company_id)->orderBy('title', 'ASC')->select('id', 'title')->get();
        $data['lead_statuses'] = DB::table('lead_statuses')->where('company_id', auth()->user()->company_id)->orderBy('title', 'ASC')->select('id', 'title')->get();
        return view('backend.leads.create', compact('data'));
    }

    // delete single data from database
    public function delete($id)
    {
        try {
            $result = $this->leadRepo->deleteLead($id);
            if ($result) {
                Toastr::success(_trans('response.Lead deleted successfully'), 'Deleted');
                return redirect()->route('lead.index');
            } else {
                Toastr::error(_trans('response.Something went wrong!'), 'Error');
                return redirect()->back();
            }
        } catch (\Throwable$th) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    // status change for all selected data
    public function statusUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        return $this->leadRepo->statusUpdate($request);
    }

    // destroy all selected data
    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->leadRepo->destroyAll($request);
    }

    //create lead

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $data['title'] = _trans('lead.Lead Create');
            $data['url'] = (hasPermission('lead_store')) ? route('lead.store') : '';

            $data['employees'] = dbTable('users', ['name', 'id', 'email'], ['company_id' => auth()->user()->company_id])->get();
            $data['lead_types'] = DB::table('lead_types')->where('company_id', auth()->user()->company_id)->orderBy('title', 'ASC')->select('id', 'title')->get();
            $data['lead_sources'] = DB::table('lead_sources')->where('company_id', auth()->user()->company_id)->orderBy('title', 'ASC')->select('id', 'title')->get();
            $data['lead_statuses'] = DB::table('lead_statuses')->where('company_id', auth()->user()->company_id)->orderBy('title', 'ASC')->select('id', 'title')->get();

            return view('backend.leads.create', compact('data'));
        } catch (\Throwable$th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }
    public function leadDetails($id)
    {

        try {
            $data['title'] = _trans('lead.Lead Details');
            $data['url'] = (hasPermission('lead_store')) ? route('lead.store') : '';
            $data['source'] = dbTable('lead_sources', ['title', 'id'], ['company_id' => auth()->user()->company_id])->get();
            $data['lead'] = $this->leadRepo->getLeadById($id);
            $data['id'] = $id;
            return view('backend.leads.leadDetails', compact('data'));
        } catch (\Throwable$th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    // leadDetailsSlug
    public function ajaxLeadDetails(Request $request)
    {
        try {
            $id = $request->lead_id ?? Lead::latest()->first()->id;
            $type = $request->type ?? 'notes';
            $lead = $this->leadRepo->getProfile($id);
            $data['url'] = route('lead.details.store');

            $types = config()->get('app.lead_menus');
            if (!in_array($type, $types)) {
                $data['title'] = ucfirst($type);
                $data['items'] = $lead;
                $data['id'] = $id;
                $data['type'] = $type;
                return view('backend.leads.details.profile', compact('data'));
            } else {
                $data['title'] = ucfirst($type);
                $data['items'] = json_decode($lead->$type, true);
                $data['index'] = count($data['items']) + 1;
                $data['id'] = $id;
                $data['type'] = $type;
                $viewPath = 'backend.leads.details.' . strtolower($type);
                return view($viewPath, compact('data'));
            }
        } catch (\Throwable$th) {
            return response()->json([
                'error' => $th->getMessage(),
                'method' => 'POST',
                'url' => route('lead.ajaxLeadDetails'),
                'data' => [
                    'request' => $request->all(),
                ],
            ]);
        }

    }

    public function download(Request $request)
    {
        try {
            $path = public_path($_GET['path']);
            return response()->download($path);
        } catch (\Throwable$th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function show(Request $request)
    {
        try {
            $path = public_path($request->path);
            return response()->download($path);
        } catch (\Throwable$th) {
            return response()->json([
                'error' => $th->getMessage(),
                'method' => 'POST',
                'url' => route('lead.download'),
                'data' => [
                    'path' => $request->path,
                ],
            ]);
        }
    }

    public function deleteAttachment(Request $request)
    {
        try {
            $path = public_path(@$request->path);
            return redirect()->back()->with('success', 'Attachment deleted successfully!');
        } catch (\Throwable$th) {
            return redirect()->back()->with('success', 'Attachment deleted Not successfully!');
        }
    }

    public function createCall(Request $request)
    {
        $data['title'] = 'Create Call Log';
        $data['url'] = url('lead/storeCall');
        $data['lead'] = $this->leadRepo->getLeadById($request->id);
        $data['id'] = $request->id;

        // return view('backend.leads.partials.createCall', compact('data'));

        return response()->json([
            'success' => true,
            'body' => view('backend.leads.partials.createCall', compact('data'))->render(),
            'title' => 'Create Call Log',
        ]);
    }

    public function add_notes($request)
    {
        return $input = [
            'body' => $request->body ?? '',
            'subject' => @$request->subject ?? '',
            'author' => authorInfo(),
            'date' => dateFormatInPlainText(date('Y-m-d h:i:sa')),
            'index' => @$request->index ?? 100,
        ];
    }
    public function add_tasks($request)
    {
        return $input = [
            'subject' => @$request->subject ?? '',
            'message' => $request->message ?? '',
            'author' => authorInfo(),
            'date' => dateFormatInPlainText(date('Y-m-d h:i:sa')),
            'status' => @$request->status ?? 'Not Started',
            'index' => @$request->index ?? 100,
        ];
    }

    public function add_reminders($request)
    {
        return $input = [
            'subject' => @$request->subject ?? '',
            'message' => $request->message ?? '',
            'author' => authorInfo(),
            'date' => dateFormatInPlainText(date('Y-m-d h:i:sa')),
            'status' => @$request->status ?? 'Not Started',
            'index' => @$request->index ?? 100,
        ];
    }

    public function add_tags($request)
    {
        return $input = [
            'name' => @$request->name ?? '',
            'author' => authorInfo(),
            'date' => dateFormatInPlainText(date('Y-m-d h:i:sa')),
            'index' => @$request->index ?? 100,
        ];
    }

    public function add_calls($request)
    {
        return $input = [
            'number' => @$request->number ?? '',
            'call_date' => dateFormatInPlainText(@$request->call_date) ?? '',
            'call_type' => @$request->call_type ?? '',
            'duration' => @$request->duration ?? '',
            'subject' => @$request->subject ?? '',
            'body' => $request->body ?? '',
            'author' => authorInfo(),
            'date' => dateFormatInPlainText(date('Y-m-d h:i:sa')),
            'index' => @$request->index ?? 100,
        ];
    }

    public function add_attachments($request)
    {

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name = $file->getClientOriginalName();
            $file_size = $file->getSize(); // get file size in bytes
            $file->move('uploads/file', $file_name);

            return $input = [
                'title' => @$request->title ?? '',
                'path' => 'uploads/file/' . $file_name,
                'size' => formatSizeUnits($file_size), // format size using helper function
                'author' => authorInfo(),
                'date' => dateFormatInPlainText(date('Y-m-d h:i:sa')),
                'index' => @$request->index ?? 100,
            ];
        }
        return [];
    }

    public function add_emails($request)
    {

        try {
            Mail::to(@$request->to_email)->cc(@$request->cc_email)->send(new MyTestMail($request->subject, $request->message));
        } catch (\Throwable$th) {
        }
        return $input = [
            'to_email' => @$request->to_email ?? '',
            'cc_email' => @$request->cc_email ?? '',
            'subject' => @$request->subject ?? '',
            'message' => $request->message ?? '',
            'author' => authorInfo(),
            'date' => dateFormatInPlainText(date('Y-m-d h:i:sa')),
            'index' => @$request->index ?? 100,
        ];
    }

    // createNote
    public function storeLeadDetails(Request $request)
    {
        $type = $request->type;
        $lead = $this->leadRepo->getLeadById($request->id);

        $functionName = 'add_' . $type;
        $input = $this->$functionName($request);

        $list = json_decode($lead->$type, true) ?? [];
        array_push($list, $input);

        $lead->$type = json_encode($list);
        $lead->save();

        try{
            Lead::CreateActivity($lead, $request, $type.' created successfully');
        }catch(\Throwable$th){
        }

        return response()->json([
            'success' => true,
            'message' => $type . ' created successfully!',
            'list' => $list,
        ]);
    }

    // leadSummaryCategoryWise
    public function leadSummaryCategoryWise(Request $request)
    {
        $data['url'] = route('ajax.lead-summary-category-wise');

        if (@$request->table=="lead_statuses") {
            $data['leads'] = DB::table('leads')
                ->join('lead_statuses', 'leads.lead_status_id', '=', 'lead_statuses.id')
                ->select('lead_statuses.title', DB::raw('count(*) as total'))
                ->groupBy('lead_status_id')
                ->take(5)
                ->get();
        }else if (@$request->table=="lead_types") {
            $data['leads'] = DB::table('leads')
                ->join('lead_types', 'leads.lead_type_id', '=', 'lead_types.id')
                ->select('lead_types.title', DB::raw('count(*) as total'))
                ->groupBy('lead_type_id')
                ->take(5)
                ->get();
        } else {
            $data['leads'] = DB::table('leads')
                ->join('lead_sources', 'leads.lead_source_id', '=', 'lead_sources.id')
                ->select('lead_sources.title', DB::raw('count(*) as total'))
                ->groupBy('lead_source_id')
                ->take(5)
                ->get();
        }

        return response()->json($data);
    }
}
