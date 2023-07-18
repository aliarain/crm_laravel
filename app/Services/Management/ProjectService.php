<?php

namespace App\Services\Management;

use App\Models\Finance\Account;
use App\Models\Finance\Deposit;
use App\Models\Management\Project;
use App\Services\Core\BaseService;
use App\Services\Task\TaskService;
use Illuminate\Support\Facades\DB;
use App\Models\Finance\Transaction;
use App\Models\Management\Discussion;
use App\Models\Expenses\PaymentMethod;
use App\Services\Management\NoteService;
use App\Models\Management\ProjectPayment;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\CurrencyTrait;
use App\Services\Management\DiscussionService;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\InvoiceGenerateTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use Illuminate\Support\Facades\Log;

class ProjectService extends BaseService
{
    use RelationshipTrait, DateHandler, InvoiceGenerateTrait, CurrencyTrait, ApiReturnFormatTrait;

    protected $discussionService;
    protected $noteService;
    protected $fileService;
    protected $taskService;
    public function __construct(Project $project, DiscussionService $discussionService, NoteService $noteService, FileService $fileService, TaskService $taskService)
    {
        $this->model = $project;
        $this->discussionService = $discussionService;
        $this->noteService = $noteService;
        $this->fileService = $fileService;
        $this->taskService = $taskService;
    }


    function userDatatable($request, $user_id)
    {
        $content = $this->model->query()->with('client:id,name', 'members')->where('company_id', auth()->user()->company_id);
        $params = [];
        $content = $content->whereHas('members', function (Builder $query) use ($user_id) {
            $query->where('user_id', $user_id);
        });
        if (@$request->status_id) {
            $params['status_id'] = $request->status_id;
        }
        if (@$request->pay) {
            $params['pay'] = $request->pay;
        }
        if (@$request->return_status) {
            $params['return_status'] = $request->return_status;
        }
        if (@$request->date) {
            $content = $content->whereMonth('date', date('m', strtotime($request->date)));
        }
        if (!is_Admin()) {
            $content = $content->whereHas('members', function (Builder $query) {
                $query->where('user_id', auth()->user()->id);
            });
        }
        $content = $content->where($params);
        $content = $content->latest()->get();

        return $this->generateDatatable($content);
    }
    function datatable($request)
    {
        $content = $this->model->query()->with('client:id,name', 'members')->where('company_id', auth()->user()->company_id);
        $params = [];
        if (@$request->status_id) {
            $params['status_id'] = $request->status_id;
        }
        if (@$request->pay) {
            $params['pay'] = $request->pay;
        }
        if (@$request->return_status) {
            $params['return_status'] = $request->return_status;
        }
        if (@$request->date) {
            $content = $content->whereMonth('date', date('m', strtotime($request->date)));
        }
        if (!is_Admin()) {
            $content = $content->whereHas('members', function (Builder $query) {
                $query->where('user_id', auth()->user()->id);
            });
        }
        $content = $content->where($params);
        $content = $content->latest()->get();

        return $this->generateDatatable($content);
    }

    function generateDatatable($content)
    {
        return datatables()->of($content)
            ->addColumn('action', function ($data) {
                $action_button = '';


                if (hasPermission('project_view')) {
                    $action_button .= '<a href="' . route('project.view', [$data->id, 'overview']) . '" class="dropdown-item"> ' . _trans('common.View') . '</a>';
                }
                if (hasPermission('project_edit')) {
                    $action_button .= '<a href="' . route('project.edit', $data->id) . '" class="dropdown-item"> ' . _trans('common.Edit') . '</a>';
                }
                if (hasPermission('project_payment') && @$data->payment != 8) {
                    $action_button .= actionButton(_trans('common.Pay'), 'mainModalOpen(`' . route('project_modal.payment', $data->id) . '`)', 'modal');
                }
                if (hasPermission('project_invoice_view')) {
                    $action_button .= '<a href="' . route('project.invoice', $data->id) . '" class="dropdown-item"> ' . _trans('common.Invoice') . '</a>';
                }

                if (hasPermission('project_delete') && @$data->payment != 8) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`admin/project/delete/`)', 'delete');
                }
                $button = '<div class="flex-nowrap">
                    <div class="dropdown">
                        <button class="btn btn-white dropdown-toggle align-text-top action-dot-btn" data-boundary="viewport" data-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">' . $action_button . '</div>
                    </div>
                </div>';
                return $button;
            })
            ->addColumn('client', function ($data) {
                return $data->client->name;
            })
            ->addColumn('priority', function ($data) {
                return '<small class="badge badge-' . @$data->priorityStatus->class . '">' . @$data->priorityStatus->name . '</small>';
            })
            ->addColumn('status', function ($data) {
                return '<small class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</small>';
            })
            ->addColumn('start_date', function ($data) {
                return showDate($data->start_date);
            })
            ->addColumn('end_date', function ($data) {
                return showDate($data->end_date);
            })
            ->addColumn('members', function ($data) {
                $membars = '';
                foreach ($data->members as $member) {
                    if (hasPermission('profile_view')) {
                        $url = route('user.profile', [$member->user->id, 'official']);
                    } else {
                        $url = '#';
                    }
                    $membars .= '<a target="_blank" href="' . $url  . '"><img data-toggle="tooltip" data-placement="top" title="' . $member->user->name . '" src="' . uploaded_asset($member->user->avatar_id) . '" class="staff-profile-image-small" ></a>';
                }
                return $membars;
            })
            ->rawColumns(array('client', 'priority', 'status', 'start_date', 'end_date', 'members', 'action'))
            ->make(true);
    }


    function store($request)
    {

        DB::beginTransaction();
        try {
            $project                           = new $this->model;
            $project->name                     = $request->name;
            $project->client_id                = $request->client_id;
            $project->date                     = date('Y-m-d');
            $project->progress                 = $request->progress;
            $project->billing_type             = $request->billing_type;
            $project->per_rate                 = @$request->per_rate ?? 0;
            $project->total_rate               = @$request->total_rate ?? 0;
            $project->estimated_hour           = $request->estimated_hour;
            $project->status_id                = $request->status;
            $project->priority                 = $request->priority;
            $project->description              = $request->content;
            $project->start_date               = $request->start_date;
            $project->end_date                 = $request->end_date;
            $project->amount                   = $request->amount;
            $project->due                      = @$request->amount;
            $project->paid                     = 0;
            $project->notify_all_users         = @$request->notify_all_users??0;
            $project->notify_all_users_email   = @$request->notify_all_users_email??0;
            $project->company_id               = auth()->user()->company_id;
            $project->created_by               = auth()->id();
            $project->invoice                  = @$this->model->query()->latest()->first()->id + 1;
            $project->save();

            //team members assign to project
            if (@$request->user_ids) {
                foreach ($request->user_ids as $user_id) {
                    DB::table('project_membars')->insert([
                        'project_id' => $project->id,
                        'company_id' => auth()->user()->company_id,
                        'user_id' => $user_id,
                        'added_by' => auth()->id(),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            \App\Models\Management\ProjectActivity::CreateActivityLog(auth()->user()->company_id, $project->id, auth()->id(), 'Created the project')->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Project created successfully.'), $project);
        } catch (\Throwable $th) {
            dd($th);
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $params                = [
                'id' => $id,
                'company_id' => auth()->user()->company_id,
            ];
            $project      = $this->model->where($params)->with('members')->first();
            if (!$project) {
                return $this->responseWithError(_trans('message.Project not found'), 'id', 404);
            }

            $project->name                     = $request->name;
            $project->client_id                = $request->client_id;
            $project->date                     = date('Y-m-d');
            $project->progress                 = $request->progress;
            $project->billing_type             = $request->billing_type;
            $project->per_rate                 = @$request->per_rate ?? 0;
            $project->total_rate               = @$request->total_rate ?? 0;
            $project->estimated_hour           = $request->estimated_hour;
            $project->status_id                = $request->status;
            $project->priority                 = $request->priority;
            $project->description              = $request->content;
            $project->start_date               = $request->start_date;
            $project->end_date                 = $request->end_date;
            $project->amount                   = $request->amount;
            $project->notify_all_users         = @$request->notify_all_users;
            $project->notify_all_users_email   = @$request->notify_all_users_email;
            $project->goal_id                  = @$request->goal_id;
            $project->save();

            //team members assign to project
            if (@$request->new_members && $request->new_members[0] != false) {
                foreach ($request->new_members as $user_id) {
                    DB::table('project_membars')->insert([
                        'project_id' => $project->id,
                        'company_id' => auth()->user()->company_id,
                        'added_by' => auth()->id(),
                        'user_id' => $user_id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            if (@$request->remove_members && $request->remove_members[0] != false) {
                $project->members()->whereIn('user_id', @$request->remove_members)->delete();
            }
            \App\Models\Management\ProjectActivity::CreateActivityLog(auth()->user()->company_id, $project->id, auth()->id(), 'Updated the project')->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Project Updated successfully.'), $project);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function delete($id)
    {
        $project = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
        if (!$project) {
            return $this->responseWithError(_trans('message.Project not found'), 'id', 404);
        }
        try {
            $project->files()->delete();
            $project->notes()->delete();
            $project->discussions()->delete();
            $project->members()->delete();
            $project->delete();
            return $this->responseWithSuccess(_trans('message.Project Delete successfully.'), $project);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    //member_delete
    function member_delete($request, $id)
    {
        try {
            $project = $this->model->with('members')->where(['id' => $request->project_id, 'company_id' => auth()->user()->company_id])->first();
            if (!$project) {
                return $this->responseWithError(_trans('Project not found'), 'id', 404);
            }
            $membar = $project->members->find($id);
            if (!$membar) {
                return $this->responseWithError(_trans('Member not found'), 'id', 404);
            }
            \App\Models\Management\ProjectActivity::CreateActivityLog(auth()->user()->company_id, $project->id, auth()->id(), 'Deleted the member')->save();
            $membar->delete();
            return $this->responseWithSuccess(_trans('message.Member Delete successfully.'), $project);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    // discussions fields

    function discussionsField()
    {
        return [
            _trans('common.ID'),
            _trans('common.Subject'),
            _trans('project.Last Activity'),
            _trans('common.Comments'),
            // _trans('project.Visible To Customer'),
            _trans('common.Date Created'),
            _trans('common.Action'),
        ];
    }
    // files fields

    function filesField()
    {
        return [
            _trans('common.ID'),
            _trans('common.Subject'),
            _trans('project.Last Activity'),
            _trans('common.Comments'),
            _trans('common.Date Created'),
            _trans('common.Action'),
        ];
    }

    function task_table($request, $id)
    {
        $request['project_id'] = $id;
        return $this->taskService->table($request);
    }





    function view($id, $slug, $request)
    {

        $project = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
        if (!$project) {
            return $this->responseWithError(_trans('Project not found'), 'id', 404);
        }
        try {
            $data['sub_title'] = _trans('project.Project Overview');
            $data['view'] = $project;
            if ($slug == 'tasks') {
                $data['sub_title']         =  _trans('project.Task Details');
                $data['tasks'] = $project;
                $data['fields']    = $this->taskService->fields();
                $data['table'] = route('project_task.table', $project->id);
                $data['url_id']        = 'task_table_url';
                $data['checkbox'] = true;
                $data['class'] = 'task_table';
                $data['status_url'] = route('hrm.task.statusUpdate');
                $data['delete_url'] = route('hrm.task.deleteData');
            } elseif ($slug == 'files') {

                $data['slug']          = _trans('project.Files');
                if (@$request->file_id) {
                    $data['sub_title']         =  _trans('project.Files Details');
                    $data['file'] = $this->fileService->where([
                        'id'         => $request->file_id,
                        'company_id' => auth()->user()->company_id,
                    ])->with('comments')->first();
                    $data['is_table']   = false;
                    if (blank($data['file'])) {
                        return $this->responseWithError(_trans('message.File not found'), 'id', 404);
                    }
                } else {

                    $data['sub_title']         =  _trans('project.Files List');
                    $data['table']             = route('project.file.datatable', $project->id);
                    $data['delete_url']        = route('project.file.deleteData', $project->id);
                    $data['url_id']            = 'file_table_url_id';
                    $data['checkbox']          = true;
                    $data['fields']            = $this->filesField();
                    $data['class']             = 'file_table';
                    $data['is_table']           = true;
                }
            } elseif ($slug == 'members') {
                $data['members'] = $project->members;
            } elseif ($slug == 'discussions') {
                $data['sub_title']         =  _trans('project.Discussions Details'); 
                $data['slug']          = _trans('project.Discussions');
                if (@$request->discussion_id) {
                    $data['discussion'] = $this->discussionService->where([
                        'id'         => $request->discussion_id,
                        'company_id' => auth()->user()->company_id,
                    ])->with('comments')->first();
                    if (blank($data['discussion'])) {
                        return $this->responseWithError(_trans('message.Discussion not found'), 'id', 404);
                    }
                    $data['is_table']   = false;
                } else {

                    $data['delete_url']        = route('project.discussion.deleteData', $project->id);
                    $data['sub_title']         =  _trans('project.Discussions List'); 
                    $data['table']         = route('project.discussion.datatable', $project->id);
                    $data['url_id']        = 'discussion_table_url_id';
                    $data['fields']        = $this->discussionsField();
                    $data['class']         = 'discussion_table';
                    $data['is_table']         = true;
                    $data['checkbox']          = true;
                }
            } elseif ($slug == 'notes') {
                $data['slug']          = _trans('project.Notes');
                $data['notes']         = $this->noteService->where(['project_id' => $project->id, 'company_id' => auth()->user()->company_id])->get();
            } elseif ($slug == 'activity') {
                $data['activity'] = DB::table('project_activities')
                    ->join('users', 'project_activities.user_id', '=', 'users.id')
                    ->select('users.name as username', 'users.avatar_id', 'project_activities.*')
                    ->where(['project_activities.project_id' => $project->id, 'project_activities.company_id' => auth()->user()->company_id])
                    ->orderBy('id', 'desc')
                    ->get();
            }
            return $this->responseWithSuccess('data retrieve', $data);
        } catch (\Throwable $th) {
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    public function status($request)
    {

        $project = $this->model->where(['id' => $request->project_id, 'company_id' => auth()->user()->company_id])->first();
        if (!$project) {
            return $this->responseWithError(_trans('message.Project not found'), 'id', 404);
        }
        try {
            $project->status_id = 27;
            $project->save();
            \App\Models\Management\ProjectActivity::CreateActivityLog(auth()->user()->company_id, $project->id, auth()->id(), 'Completed the project')->save();
            return $this->responseWithSuccess(_trans('message.Project Completed successfully.'), $project);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function pay($request, $project)
    {


        DB::beginTransaction();
        try {
            if ($project->status_id == 8) {
                return $this->responseWithError(_trans('message.Project already Paid'));
            }

            $transaction                                 = new Transaction;
            $transaction->account_id                     = $request->account;
            $transaction->company_id                     = $project->company_id;
            $transaction->date                           = date('Y-m-d');
            $transaction->description                    = @$request->description ?? 'Project Payment';
            $transaction->amount                         = $request->amount;
            $transaction->transaction_type               = 19;
            $transaction->payment                        = 8;
            $transaction->status_id                      = 8;
            $transaction->created_by                     = auth()->id();
            $transaction->updated_by                     = auth()->id();
            $transaction->save();


            //Deposit Account

            $deposit                                 = new Deposit();
            $deposit->user_id                        = auth()->id();
            $deposit->income_expense_category_id     = $request->category;
            $deposit->company_id                     = auth()->user()->company->id;
            $deposit->date                           = $request->date;
            $deposit->amount                         = $request->amount;
            $deposit->request_amount                 = $request->amount;
            $deposit->ref                            = $request->ref;
            $deposit->payment_method_id              = $request->payment_method;
            $deposit->remarks                        = $request->description;
            $deposit->pay                            = 8;
            $deposit->status_id                      = 5;
            $deposit->date                           = date('Y-m-d');
            $deposit->approver_id                    = auth()->id();
            $deposit->created_by                     = auth()->id();
            $deposit->updated_by                     = auth()->id();
            if ($request->hasFile('attachment')) {
                $deposit->attachment                 = $this->uploadImage($request->attachment, 'deposit')->id;
            }
            $deposit->transaction_id                 = $transaction->id;
            $deposit->save();

            $account = Account::findOrFail($request->account);
            $account->amount = $account->amount + $transaction->amount;
            $account->save();



            $projectPayment                        = new ProjectPayment();
            $projectPayment->project_id            = $project->id;
            $projectPayment->amount                = $request->amount;
            $projectPayment->due_amount            = $project->due - $request->amount;
            $projectPayment->company_id            = $project->company_id;
            $projectPayment->transaction_id        = $transaction->id;
            $projectPayment->payment_method_id     = $request->payment_method;
            $projectPayment->paid_by               = $project->client_id;
            $projectPayment->created_by            = auth()->id();
            $projectPayment->updated_by            = auth()->id();
            $projectPayment->payment_note          = $request->description ?? 'salary Payment';
            $projectPayment->save();



            $project->due = $project->due - $request->amount;
            $project->paid = $project->paid + $request->amount;
            // $project->updated_by = auth()->id();       
            if ($project->due <= 0) {
                $project->status_id = 8;
            } else {
                $project->status_id = 20;
            }
            $project->save();
            \App\Models\Management\ProjectActivity::CreateActivityLog(auth()->user()->company_id, $project->id, auth()->id(), 'Payment created')->save();

            DB::commit();
            return $this->responseWithSuccess(_trans('message.Project pay successfully.'), $project);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('project.Project Name'),
            _trans('project.Client'),
            _trans('project.Priority'),
            _trans('common.Start Date'),
            _trans('project.Deadline'),
            _trans('project.Members'),
            _trans('common.Status'),
            _trans('common.Action')
        ];
    }

    function table($request)
    {
        
        $data = $this->model->query()->with('client:id,name', 'members')->where('company_id', auth()->user()->company_id);
        $params = [];
        if (@$request->status) {
            $params['status_id'] = $request->status;
        }
        if (@$request->pay) {
            $params['payment'] = $request->pay;
        }
        if (@$request->return_status) {
            $params['return_status'] = $request->return_status;
        }
        if (@$request->priority) {
            $params['priority'] = $request->priority;
        }
        if (!is_Admin()) {
            $data = $data->whereHas('members', function (Builder $query) {
                $query->where('user_id', auth()->user()->id);
            });
        }
        if ($request->from && $request->to) {
            $data = $data->whereBetween('date', start_end_datetime($request->from, $request->to));
        }
        if ($request->search) {
            $data = $data->where('name', 'like', '%' . $request->search . '%');
        }
        $data = $data->where($params)->paginate($request->limit ?? 10);

        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('project_view')) {
                    $action_button .= '<a href="' . route('project.view', [$data->id, 'overview']) . '" class="dropdown-item"> <span class="icon mr-8"><i class="fa-solid fa-eye"></i></span>' . _trans('common.View') . '</a>';
                }
                if (hasPermission('project_edit')) {
                    $action_button .= '<a href="' . route('project.edit', $data->id) . '" class="dropdown-item"> <span class="icon mr-8"><i class="fa-solid fa-pen-to-square"></i></span>' . _trans('common.Edit') . '</a>';
                }
                if (hasPermission('project_payment') && @$data->payment != 8) {
                    $action_button .= actionButton(_trans('common.Pay'), 'mainModalOpen(`' . route('project_modal.payment', $data->id) . '`)', 'modal');
                }
                if (hasPermission('project_invoice_view')) {
                    $action_button .= '<a href="' . route('project.invoice', $data->id) . '" class="dropdown-item"> <span class="icon mr-8"><i class="fa-solid fa-eye"></i></span>' . _trans('common.Invoice') . '</a>';
                }

                if (hasPermission('project_delete') && @$data->payment != 8) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`admin/project/delete/`)', 'delete');
                }
                $button = ' <div class="dropdown dropdown-action">
                                <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                ' . $action_button . '
                                </ul>
                            </div>';

                return [
                    'name' => $data->name,
                    'client' => @$data->client->name,
                    'priority' => '<small class="badge badge-' . @$data->priorityStatus->class . '">' . @$data->priorityStatus->name . '</small>',
                    'start_date' =>  showDate($data->start_date),
                    'end_date' =>  showDate($data->end_date),
                    'members' => teams($data->members),
                    'id' => $data->id,
                    'status' => '<small class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</small>',
                    'action'   => $button
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

    // new functions
    // statusUpdate
    public function statusUpdate($request)
    {
        DB::beginTransaction();
        try {
            
            if (@$request->action == 'complete') {
                $projects = $this->model->where('company_id', auth()->user()->company_id)->where('status_id', '!=', 27)->whereIn('id', $request->ids);
                foreach ($projects->get() as $project) {
                    \App\Models\Management\ProjectActivity::CreateActivityLog(auth()->user()->company_id, $project->id, auth()->id(), 'project Completed')->save();
                }
                $projects->update(['status_id' => 27]);
                DB::commit();
                return $this->responseWithSuccess(_trans('message.Project completed successfully.'), $projects);
            }
            return $this->responseWithError(_trans('message.Project failed'), [], 400);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $projects = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->where('status_id', '!=', 27)->get();
                if (blank($projects)) {
                    return $this->responseWithError(_trans("message.Project can't not delete"), [], 400);
                }
                foreach ($projects as $project) {
                    $project->files()->delete();
                    $project->notes()->delete();
                    $project->discussions()->delete();
                    $project->members()->delete();
                    $project->delete();
                }
                return $this->responseWithSuccess(_trans('message.Project delete successfully.'), $projects);
            } else {
                return $this->responseWithError(_trans('message.Project not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    
}
