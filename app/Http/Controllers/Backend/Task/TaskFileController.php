<?php

namespace App\Http\Controllers\Backend\Task;

use Illuminate\Http\Request;
use App\Services\Task\TaskService;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Services\Task\TaskFileService;
use App\Http\Requests\Project\FileRequest;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Http\Requests\Project\CommentRequest;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class TaskFileController extends Controller
{
    use FileHandler, ApiReturnFormatTrait;

    protected $fileService;
    protected $taskService;

    public function __construct(TaskFileService $fileService, TaskService $taskService)
    {
        $this->fileService = $fileService;
        $this->taskService    = $taskService;
    }
    // file table 
    public function table(Request $request, $id)
    {
        return $this->fileService->table($request, $id);
    }

    public function create(Request $request)
    {
        try {
            $result       = $this->taskService->where([
                'id' => $request->task_id,
                'company_id' => auth()->user()->company_id,
            ])->first();
            if (@$result) {
                $data['title']    = _trans('project.Create File');
                $data['view']     = $result;
                $data['url']      = (hasPermission('task_file_store')) ? route('task.file.store', 'task_id=' . $request->task_id) : '';
                $data['button']   = _trans('common.Submit');
                return view('backend.project.file.createModal', compact('data'));
            } else {
                return response()->json('fail');
            }
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function store(FileRequest $request)
    {
        try {
            $result = $this->fileService->store($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return $this->responseWithSuccess($result->original['message'], [], 200);
            } else {
                Toastr::error($result->original['message'], 'Error');
                return $this->responseWithError($result->original['message'], [], 400);
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return $this->responseWithError('response.Something went wrong.', [], 400);
            // return redirect()->back();
        }
    }

    public function comment(CommentRequest $request)
    {
        try {
            return $this->fileService->commentStore($request);
        } catch (\Throwable $th) {
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }


    // file download
    public function download(Request $request)
    {
        try {

            $result       = $this->fileService->where([
                'id' => $request->file_id,
                'company_id' => auth()->user()->company_id,
                'task_id' => $request->task_id,
            ])->first();
            if (@$result) {
                return $this->downloadFile($result->attachment ?? null, $result->subject);
            } else {
                Toastr::error(_trans('response.Something went wrong.'), 'Error');
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
            $result = $this->fileService->delete($id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('task.view', [$result->original['data']->task_id, 'files']);
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
        return $this->fileService->destroyAll($request);
    }
}
