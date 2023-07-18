<?php

namespace App\Http\Controllers\Backend\Task;

use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Services\Task\TaskService;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class TaskController extends Controller
{
    use ApiReturnFormatTrait;
    protected $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        try {
            $data['title']     = _trans('task.Tasks List');
            $data['fields']    = $this->taskService->fields();

            if ($request->ajax()) {
                return $this->taskService->table($request);
            }
            $data['table'] = route('task.index');
            $data['url_id']        = 'task_table_url';
            $data['checkbox'] = true;
            $data['class'] = 'task_table';
            $data['status_url'] = route('hrm.task.statusUpdate');
            $data['delete_url'] = route('hrm.task.deleteData');

            return view('backend.task.index', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function table(Request $request)
    {
        return $this->taskService->table($request);
    }

    // create a new task
    public function create(Request $request)
    {
        try {
            $data['title']    = _trans('task.Task Create');
            $data['url']      = (hasPermission('task_store')) ? route('task.store') : '';
            $data['clients']  = dbTable('clients', ['name', 'id'], ['company_id' => auth()->user()->company_id])->get();
            return view('backend.task.create', compact('data', 'request'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function store(TaskRequest $request)
    {
        try {
            $result = $this->taskService->store($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                if (@$request->project_id) {
                    return redirect()->route('project.view', [$request->project_id, 'tasks']);
                }
                return redirect()->route('task.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $params                = [
                'id' => $id,
                'company_id' => auth()->user()->company_id,
            ];
            $data['edit']       = $this->taskService->where($params)->with('members')->first();
            if (!$data['edit']) {
                Toastr::error('Task not found!', 'Error');
                return redirect()->back();
            }
            $data['title']    = _trans('task.Task Edit');
            $data['url']      = (hasPermission('task_update')) ? route('task.update', [$data['edit']->id]) : '';
            return view('backend.task.edit', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request, $id)
    {
        try {
            $result = $this->taskService->update($request, $id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('task.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function view(Request $request, $id, $slug)
    {
        try {
            $result       = $this->taskService->view($id, $slug, $request);
            if ($result->original['result']) {
                $title    = _trans('task.Task View');
                $data = $result->original['data'];
                return view('backend.task.view', compact('data', 'title'));
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function member_delete(Request $request, $id)
    {
        try {
            $result = $this->taskService->member_delete($request, $id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('task.view', [$request->task_id, 'members']);
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }


    public function complete(Request $request)
    {
        try {
            $result = $this->taskService->status($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('task.view', [$request->task_id, 'details']);
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->taskService->delete($id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('task.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    // status change
    public function statusUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        return $this->taskService->statusUpdate($request);
    }

    public function taskUpdateStatus(Request $request){
       if($request->all() == null){
        $request['ids'] = [1,2];
        $request['action'] = 'complete';
       }
        return $this->taskService->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->taskService->destroyAll($request);
    }

    public function userProfileTable(Request $request)
    {
        if ($request->ajax()) {
            return $this->taskService->table($request);
        }
    }
}
