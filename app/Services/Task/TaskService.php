<?php

namespace App\Services\Task;

use App\Services\Core\BaseService;
use Illuminate\Support\Facades\DB;
use App\Models\TaskManagement\Task;
use App\Services\Task\TaskFileService;
use App\Services\Task\TaskNoteService;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Services\Task\TaskDiscussionService;
use App\Helpers\CoreApp\Traits\CurrencyTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\InvoiceGenerateTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use Illuminate\Support\Facades\Log;

class TaskService extends BaseService
{
    use RelationshipTrait, DateHandler, InvoiceGenerateTrait, CurrencyTrait, ApiReturnFormatTrait;

    protected $discussionService;
    protected $noteService;
    protected $fileService;
    public function __construct(Task $task, TaskDiscussionService $discussionService, TaskNoteService $noteService, TaskFileService $fileService)
    {
        $this->model = $task;
        $this->discussionService = $discussionService;
        $this->noteService = $noteService;
        $this->fileService = $fileService;
    }

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('task.Name'),
            _trans('common.Description'),
            _trans('common.Status'),
            _trans('common.Start Date'),
            _trans('project.End Date'),
            _trans('common.Assigned To'),
            _trans('project.Priority'),
            _trans('common.Action')

        ];
    }


    function userDatatable($request, $user_id)
    {
        $where = [];
        if ($request->project_id) {
            $where = ['type' => 1, 'project_id' => $request->project_id];
        }
        $content =  $this->model->with('members', 'status')->where(['company_id' => auth()->user()->company_id])->where($where);

        $content = $content->whereHas('members', function (Builder $query) use ($user_id) {
            $query->where('user_id', $user_id);
        });
        $content = $content->paginate($request->limit ?? 10);

        return $this->generateDatatable($content);
    }
    function table($request)
    {
        
        $where = [];
        if ($request->project_id) {
            $where = ['type' => 1, 'project_id' => $request->project_id];
        }
        $content =  $this->model->with('members', 'status')->where(['company_id' => auth()->user()->company_id])->where($where);
        if (!is_Admin()) {
            $content = $content->whereHas('members', function (Builder $query) {
                $query->where('user_id', auth()->user()->id);
            });
        }
        if ($request->user_id) {
            $content = $content->whereHas('members', function (Builder $query) use ($request) {
                $query->where('user_id', $request->user_id);
            });
        }
        if ($request->from && $request->to) {
            $content = $content->whereBetween('date', start_end_datetime($request->from, $request->to));
        }
        if ($request->search) {
            $content = $content->where('name', 'like', '%' . $request->search . '%');
        }
        $content = $content->paginate($request->limit ?? 10);

        return $this->generateDatatable($content);
    }
    function generateDatatable($content)
    {
        try {
            return [
                'data' => $content->map(function ($data) {
                    $action_button = '';
                    if (hasPermission('task_view')) {
                        $action_button .= '<a href="' . route('task.view', [$data->id, 'details']) . '" class="dropdown-item"> <span class="icon mr-8"><i class="fa-solid fa-eye"></i></span>' . _trans('common.View') . '</a>';
                    }
                    if (hasPermission('task_edit')) {
                        $action_button .= '<a href="' . route('task.edit', [$data->id]) . '" class="dropdown-item"> <span class="icon mr-8"><i class="fa-solid fa-pen-to-square"></i></span>' . _trans('common.Edit') . '</a>';
                    }
                    if (hasPermission('task_delete')) {
                        $action_button .= actionButton( _trans('common.Delete'), '__globalDelete(' . $data->id . ',`admin/task/delete/`)', 'delete');
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
    
                    //SHOWING MEMBERS IMAGES IN TASK LIST
                    $membars = '';
                    foreach ($data->members as $member) {
                        if (hasPermission('profile_view') && isset($member->user)) {
                            $url = route('user.profile', [@$member->user->id, 'official']);
                        } else {
                            $url = '#';
                        }
                        $membars .= '<a target="_blank" href="' . $url  . '"><img data-toggle="tooltip" data-placement="top" title="' . @$member->user->name . '" src="' . uploaded_asset(@$member->user->avatar_id) . '" class="staff-profile-image-small" ></a>';
                    }

                 

                    return [
                        'id' => $data->id,
                        'name' => $data->name,
                        'description' => '<p>'. $data->description.'</p>',
                        'date' => showDate($data->created_at),
                        'start_date' => showDate($data->start_date),
                        'end_date' => showDate($data->end_date),
                        'priority' => '<small class="badge badge-' . @$data->priorityStatus->class . '">' . @$data->priorityStatus->name . '</small>',
                        'status' => '<small class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</small>',
                        'assignee' => $membars,
                        'action'   => $button
                    ];
                }),
                'pagination' => [
                    'total' => $content->total(),
                    'count' => $content->count(),
                    'per_page' => $content->perPage(),
                    'current_page' => $content->currentPage(),
                    'total_pages' => $content->lastPage(),
                    'pagination_html' =>  $content->links('backend.pagination.custom')->toHtml(),
                ],
            ];
        } catch (\Throwable $th) {
        }
    }

    function store($request)
    {
        DB::beginTransaction();
        try {
            $task                           = new $this->model;
            $task->name                     = $request->name;
            $task->date                     = date('Y-m-d');
            $task->progress                 = $request->progress;
            $task->status_id                = $request->status;
            $task->priority                 = $request->priority;
            $task->description              = $request->content;
            $task->start_date               = $request->start_date;
            $task->end_date                 = $request->end_date;
            $task->notify_all_users         = $request->notify_all_users ?? 0;
            $task->notify_all_users_email   = $request->notify_all_users_email ?? 0;
            $task->company_id               = auth()->user()->company_id;
            $task->created_by               = auth()->id();
            if (@$request->project_id) {
                $task->project_id           = $request->project_id;
                $task->type                 = 1;
                \App\Models\Management\ProjectActivity::CreateActivityLog(auth()->user()->company_id, $request->project_id, auth()->id(), 'Created the Task')->save();
            }
            $task->save();

            //team members assign to task
            if (@$request->user_ids) {
                foreach ($request->user_ids as $user_id) {
                    DB::table('task_members')->insert([
                        'task_id' => $task->id,
                        'company_id' => auth()->user()->company_id,
                        'user_id' => $user_id,
                        'added_by' => auth()->id(),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            \App\Models\TaskManagement\TaskActivity::CreateActivityLog(auth()->user()->company_id, $task->id, auth()->id(), 'Created the Task')->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Task created successfully.'), $task);
        } catch (\Throwable $th) {
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
            $task      = $this->model->where($params)->with('members')->first();
            if (!$task) {
                return $this->responseWithError(_trans('Task not found'), 'id', 404);
            }

            $task->name                     = $request->name;
            $task->progress                 = $request->progress;
            $task->status_id                = $request->status;
            $task->priority                 = $request->priority;
            $task->description              = $request->content;
            $task->start_date               = $request->start_date;
            $task->end_date                 = $request->end_date;
            $task->notify_all_users         = $request->notify_all_users;
            $task->notify_all_users_email   = $request->notify_all_users_email;
            $task->goal_id                  = @$request->goal_id;

            if (@$task->project_id) {
                \App\Models\Management\ProjectActivity::CreateActivityLog(auth()->user()->company_id, $task->project_id, auth()->id(), 'Updated the Task')->save();
            }
            $task->save();

            //team members assign to project
            if (@$request->new_members && $request->new_members[0] != false) {
                foreach ($request->new_members as $user_id) {
                    DB::table('task_members')->insert([
                        'task_id' => $task->id,
                        'company_id' => auth()->user()->company_id,
                        'added_by' => auth()->id(),
                        'user_id' => $user_id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            if (@$request->remove_members && $request->remove_members[0] != false) {
                $task->members()->whereIn('user_id', @$request->remove_members)->delete();
            }
            \App\Models\TaskManagement\TaskActivity::CreateActivityLog(auth()->user()->company_id, $task->id, auth()->id(), 'Updated the task')->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Task Updated successfully.'), $task);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function delete($id)
    {
        $task = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
        if (!$task) {
            return $this->responseWithError(_trans('message.Task not found'), 'id', 404);
        }
        try {
            if (@$task->project_id) {
                \App\Models\Management\ProjectActivity::CreateActivityLog(auth()->user()->company_id, $task->project_id, auth()->id(), 'Delete the Task')->save();
            }
            $task->files()->delete();
            $task->notes()->delete();
            $task->discussions()->delete();
            $task->members()->delete();
            $task->delete();
            return $this->responseWithSuccess(_trans('message.Task Delete successfully.'), $task);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    //member_delete
    function member_delete($request, $id)
    {
        try {
            $task = $this->model->with('members')->where(['id' => $request->task_id, 'company_id' => auth()->user()->company_id])->first();
            if (!$task) {
                return $this->responseWithError(_trans('Task not found'), 'id', 404);
            }
            $membar = $task->members->find($id);
            if (!$membar) {
                return $this->responseWithError(_trans('Member not found'), 'id', 404);
            }
            \App\Models\TaskManagement\TaskActivity::CreateActivityLog(auth()->user()->company_id, $task->id, auth()->id(), 'Deleted the member')->save();
            $membar->delete();
            return $this->responseWithSuccess(_trans('message.Member Delete successfully.'), $task);
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





    function view($id, $slug, $request)
    {

        $task = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
        if (!$task) {
            return $this->responseWithError(_trans('Task not found'), 'id', 404);
        }
        try {
            $data['view'] = $task;
            $data['slug']          = _trans('task.Task Overview');
            if ($slug == 'tasks') {
                $data['tasks'] = $task;
            } elseif ($slug == 'files') {

                $data['slug']          = _trans('project.Files');
                if (@$request->file_id) {
                    $data['sub_title']         =  _trans('project.Files Details'); 
                    $data['file'] = $this->fileService->where([
                        'id'         => $request->file_id,
                        'company_id' => auth()->user()->company_id,
                    ])->with('comments')->first();
                    if (blank($data['file'])) {
                        return $this->responseWithError(_trans('message.File not found'), 'id', 404);
                    }
                    $data['is_table']   = false;
                } else {
                    $data['sub_title']         =  _trans('project.Files List'); 
                    $data['table']             = route('task.file.table', $task->id);
                    $data['delete_url']        = route('task.file.deleteData', $task->id);
                    $data['url_id']            = 'file_table_url_id';
                    $data['checkbox']          = true;
                    $data['fields']            = $this->filesField();
                    $data['class']             = 'file_table';
                    $data['is_table']             = true;
                }
            } elseif ($slug == 'members') {
                $data['members'] = $task->members;
            } elseif ($slug == 'discussions') {
                $data['slug']          = _trans('project.Discussions');
                if (@$request->discussion_id) {
                    $data['sub_title']         =  _trans('project.Discussions Details'); 
                    $data['discussion'] = $this->discussionService->where([
                        'id'         => $request->discussion_id,
                        'company_id' => auth()->user()->company_id,
                    ])->with('comments')->first();
                    if (blank($data['discussion'])) {
                        return $this->responseWithError(_trans('message.Discussion not found'), 'id', 404);
                    }
                    $data['is_table']   = false;
                } else {
                    $data['delete_url']        = route('task.discussion.deleteData', $task->id);
                    $data['sub_title']         =  _trans('project.Discussions List'); 
                    $data['table']         = route('task.discussion.table', $task->id);
                    $data['url_id']        = 'discussion_table_url_id';
                    $data['fields']        = $this->discussionsField();
                    $data['class']         = 'discussion_table';
                    $data['is_table']         = true;
                    $data['checkbox']          = true;
                }
            } elseif ($slug == 'notes') {
                $data['slug']          = _trans('project.Notes');
                $data['notes']         = $this->noteService->where(['task_id' => $task->id, 'company_id' => auth()->user()->company_id])->get();
            } elseif ($slug == 'activity') {
                $data['activity'] = DB::table('task_activities')
                    ->join('users', 'task_activities.user_id', '=', 'users.id')
                    ->select('users.name as username', 'users.avatar_id', 'task_activities.*')
                    ->where(['task_activities.task_id' => $task->id, 'task_activities.company_id' => auth()->user()->company_id])
                    ->orderBy('id', 'desc')
                    ->get();
            }
            return $this->responseWithSuccess('data retrieve', $data);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function status($request)
    {

        $task = $this->model->where(['id' => $request->task_id, 'company_id' => auth()->user()->company_id])->first();
        if (!$task) {
            return $this->responseWithError(_trans('message.Task not found'), 'id', 404);
        }
        try {

            $task->status_id = 27;
            $task->save();
            \App\Models\TaskManagement\TaskActivity::CreateActivityLog(auth()->user()->company_id, $task->id, auth()->id(), 'Task Completed')->save();
            return $this->responseWithSuccess(_trans('message.Task completed successfully.'), $task);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    // status Update
    public function statusUpdate($request)
    {
        DB::beginTransaction();
        try {
            if (@$request->action == 'complete') {
                $category = $this->model->where('company_id', auth()->user()->company_id)->where('status_id', '!=', 27)->whereIn('id', $request->ids);
                foreach ($category->get() as $task) {
                    \App\Models\TaskManagement\TaskActivity::CreateActivityLog(auth()->user()->company_id, $task->id, auth()->id(), 'Task Completed')->save();
                }
                $category->update(['status_id' => 27]);
                DB::commit();
                return $this->responseWithSuccess(_trans('message.Task completed successfully.'), $category);
            }
            return $this->responseWithError(_trans('message.Task failed'), [], 400);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $category = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->get();
                foreach ($category as $task){
                    if (@$task->project_id) {
                        \App\Models\Management\ProjectActivity::CreateActivityLog(auth()->user()->company_id, $task->project_id, auth()->id(), 'Delete the Task')->save();
                    }
                    $task->files()->delete();
                    $task->notes()->delete();
                    $task->discussions()->delete();
                    $task->members()->delete();
                    $task->delete();
                }
                return $this->responseWithSuccess(_trans('message.Task delete successfully.'), $category);
            } else {
                return $this->responseWithError(_trans('message.Task not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    
}
