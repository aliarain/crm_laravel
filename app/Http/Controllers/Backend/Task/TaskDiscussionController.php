<?php

namespace App\Http\Controllers\Backend\Task;

use Illuminate\Http\Request;
use App\Services\Task\TaskService;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Services\Task\TaskDiscussionService;
use App\Http\Requests\Project\CommentRequest;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class TaskDiscussionController extends Controller
{
    use ApiReturnFormatTrait;
    protected $discussionService;
    protected $taskService;

    public function __construct(TaskDiscussionService $discussionService, TaskService $taskService)
    {
        $this->discussionService = $discussionService;
        $this->taskService    = $taskService;
    }

    // discussion table 
    public function table(Request $request, $id)
    {
        return $this->discussionService->table($request, $id);
    }

    public function create(Request $request)
    {
        try {
            $result       = $this->taskService->where([
                'id' => $request->task_id,
                'company_id' => auth()->user()->company_id,
            ])->first();
            if (@$result) {
                $data['title']    = _trans('project.Create Discussion');
                $data['view'] = $result;
                $data['url']      = (hasPermission('task_discussion_store')) ? route('task.discussion.store', 'task_id=' . $request->task_id) : '';
                return view('backend.project.discussion.createModal', compact('data'));
            } else {
                return response()->json('fail');
            }
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function store(Request $request)
    {
        try {
            return $this->discussionService->store($request);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function view(Request $request)
    {
        try {
            $data['title']     = _trans('payroll.Discussion View');
            $params                = [
                'id' => $request->discussion_id,
                'company_id' => auth()->user()->company_id
            ];
            $data['discussion']       = $this->discussionService->where($params)->first();
            if (@$data['discussion']) {
                return view('backend.project.discussion.view', compact('data'));
            } else {
                Toastr::error(_trans('response.Discussion Not Found'), 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function comment(CommentRequest $request)
    {
        try {
            return $this->discussionService->commentStore($request);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    public function delete($id)
    {
        try {
            $result = $this->discussionService->delete($id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('task.view', [$result->original['data']->task_id, 'discussions']);
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->discussionService->destroyAll($request);
    }
}
