<?php

namespace App\Http\Controllers\Backend\Support;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Hrm\Support\SupportTicket;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Repositories\Support\SupportTicketRepository;

class SupportController extends Controller
{
    use ApiReturnFormatTrait;

    protected $support;

    public function __construct(SupportTicketRepository $repository)
    {
        $this->support = $repository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->support->table($request);
        }
        $data['fields']        = $this->support->fields();
        $data['checkbox']      = true;
        $data['delete_url']    = route('supportTicket.delete_data');
        $data['table']         = route('supportTicket.index');
        $data['url_id']        = 'support_table_url';
        $data['class']         = 'table_class';


        $data['title'] = _trans('support.Support ticket');
        $data['id'] = auth()->id();
        return view('backend.support.index', compact('data'));
    }

    public function create()
    {
        $data['title'] = _trans('support.Create support ticket');
        $data['id'] = auth()->id();
        $data['url'] = route('supportTicket.dataTable');
        return view('backend.support.create', compact('data'));
    }

    public function store(Request $request)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        $ticket = $this->support->store($request);
        try {
            if ($ticket->original['result']) {
                Toastr::success(_trans('response.Support ticket created successfully'), 'Success');
            } else {
                Toastr::error($ticket->original['message'], 'Error');
            }
            return redirect()->route('supportTicket.index');
        } catch (\Throwable $throwable) {
            Toastr::error(_trans('response.Something went wrong'), 'Error');
            return redirect()->back();
        }
    }


    public function ticketReplyStore(Request $request, SupportTicket $ticket)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        try {
            $ticket = $this->support->ticketReply($request, $ticket);
            if ($ticket->original['result']) {
                Toastr::success(_trans('response.Support ticket updated successfully'), 'Success');
            } else {
                Toastr::error($ticket->original['message'], 'Error');
            }
            return redirect()->back();
        } catch (\Throwable $throwable) {
            Toastr::error(_trans('response.Something went wrong'), 'Error');
            return redirect()->back();
        }
    }

    public function staffTicket()
    {
        $data['title'] = _trans('support.Support ticket');
        $data['id'] = auth()->id();
        $data['url'] = route('supportTicket.index.dataTable', auth()->id());
        return view('backend.user.support_ticket', compact('data'));
    }


    public function ticketReply(SupportTicket $ticket, $code)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        try {
            $data['title'] = _trans('support.Support ticket reply');
            if ($ticket->code != decrypt($code)) {
                return abort(400);
            }
            $data['show'] = $ticket->load('user', 'supportTickets.user');
            return view('backend.support.reply', compact('data'));
        } catch (\Throwable $exception) {
            Toastr::error(_trans('response.Something went wrong'), 'Error');
            return redirect()->back();
        }
    }




    public function dataTable(Request $request)
    {
        return $this->support->dataTable($request);
    }

    public function userTicketDatatable(Request $request, $id)
    {
        return $this->support->dataTable($request, $id);
    }
    public function assignTicket(Request $request,$id)
    {
        try {
            if ($request->method()=='POST') {
                $ticket = $this->support->assignTicket($request,$id);
                if ($ticket) {
                    return response()->json(['success' => true, 'message' => _trans('response.Support ticket updated successfully')]);
                } else {
                    return response()->json('fail');
                }
            } else {
                $data['edit']               = $this->support->getById($id);
                $data['title']              = _trans('support.Assign Employee To Ticket');
                $data['url']                = (hasPermission('support_assign')) ? route('supportTicket.assign', $id) : '';
                $data['users']              = dbTable('users', ['name', 'id'], ['company_id' => auth()->user()->company_id])->get();
                @$data['button']            = _trans('support.Assign');
                return view('backend.support.assignModal', compact('data'));
            }
            
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }
    public function delete($id)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        try {
            $ticketDelete = $this->support->destroy($id);
            if ($ticketDelete) {
                Toastr::success(_trans('response.Support ticket deleted successfully'), 'Success');
            } else {
                Toastr::error(_trans('response.Ticket did not deleted'), 'Error');
            }
            return redirect()->back();
        } catch (\Throwable $throwable) {
            Toastr::error(_trans('response.Something went wrong'), 'Error');
            return redirect()->back();
        }
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->support->destroyAll($request);
    }

    public function userProfileTable(Request $request)
    {
        if ($request->ajax()) {
            return $this->support->table($request);
        }
    }
}
