<?php

namespace App\Repositories\Support;

use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Helpers\CoreApp\Traits\InvoiceGenerateTrait;
use App\Http\Resources\Hrm\SupportTicketCollection;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Models\Hrm\Support\SupportTicket;
use App\Models\Hrm\Support\TicketReply;
use Illuminate\Database\Eloquent\Builder;
use Validator;

class SupportTicketRepository
{
    use RelationshipTrait, FileHandler, ApiReturnFormatTrait, InvoiceGenerateTrait, DateHandler;

    protected $support;

    public function __construct(SupportTicket $support)
    {
        $this->support = $support;
    }

    public function ticketList($request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Required field missing'), $validator->errors(), 422);
        }
        $month = $this->onlyMonth($request->month);

        $tickets = $this->support->query()->where('user_id', auth()->id())->whereMonth('created_at', $month);
        $tickets->when($request->type, function (Builder $builder) use ($request) {
            $builder->where('type_id', $request->type);
        });
        $tickets = $tickets->get();
        $data = new SupportTicketCollection($tickets);
        return $this->responseWithSuccess('Ticket list', $data, 200);
    }
    function getById($id) {
        return $this->support->query()->where('id', $id)->first();
    }
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $ticket = $this->support->query()->where(['id' => $id, 'user_id' => auth()->id()])->first();
        if ($ticket) {
            $data['code'] = $ticket->code;
            $data['subject'] = $ticket->subject;
            $data['description'] = $ticket->description;
            $data['type_name'] = @$ticket->type->name;
            $data['type_color'] = appColorCodePrefix() . @$ticket->type->color_code;
            $data['priority_name'] = @$ticket->priority->name;
            $data['priority_color'] = appColorCodePrefix() . @$ticket->priority->color_code;
            $data['date'] = $this->dateFormatInPlainText($ticket->created_at);
            $data['file'] = uploaded_asset($ticket->attachment_file_id);

            return $this->responseWithSuccess('Ticket view', $data, 200);
        } else {
            return $this->responseWithError('No data found', [], 400);
        }
    }

    public function store($request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|max:50',
            'description' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Required fields are missing'), $validator->errors(), 422);
        }
        $support = new $this->support();
        $support->code = $this->generateCode($this->support, 'ST');
        $support->user_id = auth()->id();
        $support->company_id = $this->companyInformation()->id;
        $support->type_id = 12;
        $support->priority_id = $request->priority_id;
        $support->subject = $request->subject;
        $support->date = date('Y-m-d');
        $support->description = $request->description;
        if ($request->hasFile('attachment_file')) {
            $support->attachment_file_id = $this->uploadImage($request->attachment_file, 'uploads/supportTicket')->id;
        }
        $support->save();
        if ($support) {
            return $this->responseWithSuccess('Ticket created successfully', [], 200);
        } else {
            return $this->responseWithError('Ticket dose not created', [], 400);
        }
    }

    public function ticketReply($request, $ticket)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Required fields are missing'), $validator->errors(), 422);
        }

        $ticket->type_id = $request->type_id;
        $ticket->save();
        if ($ticket) {
            $ticket->supportTickets()->create([
                'user_id' => auth()->id(),
                'message' => $request->message
            ]);
            return $this->responseWithSuccess(_trans('response.Support ticket replied successfully'), [], 200);
        } else {
            return $this->responseWithError('Dose not created', [], 400);
        }
    }

    public function staffSupportDataTable($request, $id = null)
    {
        $tickets = $this->support->query()->where('user_id', auth()->user()->id);
        $tickets->when(\request()->get('date'), function (Builder $builder) {
            $date = explode(' - ', \request()->get('date'));
            return $builder->whereBetween('date', [$this->databaseFormat($date[0]), $this->databaseFormat($date[1])]);
        });
        if (auth()->user()->role->slug == 'staff') {
            $tickets = $tickets->where('user_id', auth()->id());
        }
        return $this->suportDatatable($tickets);
    }
    public function dataTable($request, $id = null)
    {
        $tickets = $this->support->query();
        $tickets->when(\request()->get('date'), function (Builder $builder) {
            $date = explode(' - ', \request()->get('date'));
            return $builder->whereBetween('date', [$this->databaseFormat($date[0]), $this->databaseFormat($date[1])]);
        });
        $tickets->when($id, function (Builder $builder) use ($id) {
            return $builder->where('user_id', $id);
        });
        if (auth()->user()->role->slug == 'staff') {
            $tickets = $tickets->where('user_id', auth()->id());
        }
        return $this->suportDatatable($tickets);
    }

    public function suportDatatable($tickets)
    {
        return datatables()->of($tickets->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';

                if (hasPermission('support_reply')) {
                    $action_button .= '<a href="' . route('supportTicket.reply', [$data->id, encrypt($data->code)]) . '" class="dropdown-item">Reply</a>';
                }
                    $action_button .= '<a href="' . route('supportTicket.assign', [$data->id, encrypt($data->code)]) . '" class="dropdown-item">'._trans('support.Assign').'</a>';
                if (hasPermission('support_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/support/ticket/delete/`)', 'delete');
                }

                if (hasPermission('support_delete')) {
                    $button = '<div class="flex-nowrap">
                <div class="dropdown">
                    <button class="btn btn-white dropdown-toggle align-text-top action-dot-btn" data-boundary="viewport" data-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">' . $action_button . '</div>
                </div>
            </div>';
                    return $button;
                }
            })
            ->addColumn('date', function ($data) {
                return @$data->date;
            })
            ->addColumn('code', function ($data) {
                return @$data->code;
            })
            ->addColumn('employee_name', function ($data) {
                return @$data->user->name;
            })
            ->addColumn('subject', function ($data) {
                return @$data->subject;
            })
            ->addColumn('type', function ($data) {
                return '<span class="badge badge-' . @$data->type->class . '">' . @$data->type->name . '</span>';
            })
            ->addColumn('priority', function ($data) {
                return '<span class="badge badge-' . @$data->priority->class . '">' . @$data->priority->name . '</span>';
            })
            ->rawColumns(array('date', 'code', 'employee_name', 'subject', 'employee_name', 'type', 'priority', 'action'))
            ->make(true);
    }

    public function destroy($id)
    {
        $ticket = $this->support->query()->find($id);
        if ($ticket) {
            $ticket->delete();
            return true;
        } else {
            return false;
        }
    }

    // new functions

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Date'),
            _trans('common.Code'),
            _trans('common.Employee name'),
            _trans('common.Subject'),
            _trans('common.Type'),
            _trans('common.Priority'),
            _trans('common.Action')
        ];
    }

    function table($request){
        $data = $this->support->query()->where('company_id', auth()->user()->company_id);
        if ($request->from && $request->to) {
            $data = $data->whereBetween('date', start_end_datetime($request->from, $request->to));
        }
        $data->when(\request()->get('user_id'), function ($query) {
            return $query->where('user_id', \request()->get('user_id'))->orWhere('assigned_id', \request()->get('user_id'));
        });
        $data->when(\request()->get('status'), function ($query) {
            return $query->where('status', \request()->get('status'));
        });

        if ($request->search) {
            $data = $data->where('subject', 'like', '%' . $request->search . '%');
        }
        $data = $data->orderBy('id', 'desc')->paginate(10);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('support_reply')) {
                    $action_button .= '<a href="' . route('supportTicket.reply', [$data->id, encrypt($data->code)]) . '" class="dropdown-item"> <span class="icon mr-8"><i class="fa-solid fa-reply"></i></span>Reply</a>';
                }
                if (hasPermission('support_assign')) {
                    $action_button .= actionButton(_trans('support.Assign'), 'mainModalOpen(`' . route('supportTicket.assign', $data->id) . '`)', 'modal');
                }
                if (hasPermission('support_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/support/ticket/delete/`)', 'delete');
                }

                return [
                    'id'            => $data->id,
                    'employee_name'  => @$data->user->name,
                    'date'       => showDate($data->date),
                    'code'       => @$data->code,
                    'subject'       => @$data->subject,
                    'type'       => '<span class="badge badge-' . @$data->type->class . '">' . @$data->type->name . '</span>',
                    'priority'     => '<span class="badge badge-' . @$data->priority->class . '">' . @$data->priority->name . '</span>',
                    'action'     => actionHTML($action_button)
                ];
            }),
            'pagination' => [
                'total' => $data->total(),
                'count' => $data->count(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
                'pagination_html' =>  $data->links('backend.pagination.custom')->toHtml(),
            ],
        ];
    }

    function assignTicket($request,$id)
    {
        $ticket = $this->support->query()->find($id);
        if ($ticket) {
            $ticket->assigned_id = $request->assigned_id;
            $ticket->save();
            return true;
        } else {
            return false;
        }
    }
    
    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $support = $this->support->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                return $this->responseWithSuccess(_trans('message.Support Ticket delete successfully.'), $support);
            } else {
                return $this->responseWithError(_trans('message.Support Ticket not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
