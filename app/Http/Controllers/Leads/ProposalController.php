<?php

namespace App\Http\Controllers\Leads;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Leads\ProposalRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class ProposalController extends Controller
{
    use ApiReturnFormatTrait;
    protected $proposalRepo;

    public function __construct( ProposalRepository $proposalRepo )
    {
        $this->proposalRepo = $proposalRepo;
    } 
    public function index(Request $request){

        $data = [
            'fields' => $this->proposalRepo->fields(), // Get the fields for the proposals table
            // Set the properties for the view data
            'checkbox' => true, // Show the checkbox for the table
            'class' => 'proposals_datatable', // Class for the table
            'status_url' => route('proposal.statusUpdate'),
            'delete_url' => route('proposal.deleteData'),
            'title' => _trans('proposal.Proposal List'),
        ];

        if ($request->ajax()) {
            return $this->proposalRepo->table($request);
        }
        // Return the view with the data
        return view('backend.leads.proposal.index', compact('data'));
    }
}
