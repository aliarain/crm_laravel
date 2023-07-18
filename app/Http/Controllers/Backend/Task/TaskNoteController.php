<?php

namespace App\Http\Controllers\Backend\Task;

use Illuminate\Http\Request;
use App\Services\Task\TaskService;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Services\Task\TaskNoteService;

class TaskNoteController extends Controller
{
    
    protected $noteService;
    protected $taskService;

    public function __construct(TaskNoteService $noteService, TaskService $taskService)
    {
        $this->noteService = $noteService;
        $this->taskService    = $taskService;
    }

    public function create(Request $request)
    {
        try {
            $result       = $this->taskService->where([
                'id' => $request->task_id,
                'company_id' => auth()->user()->company_id,
            ])->first();
            if (@$result) {
                $data['title']    = _trans('project.Create Note');
                $data['view']     = $result;
                $data['url']      = (hasPermission('task_notes_store')) ? route('task.note.store', 'task_id=' . $request->task_id) : '';
                $data['button']   = _trans('common.Submit');
                return view('backend.project.note.createModal', compact('data'));
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
            $result = $this->noteService->store($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->back();
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }
    public function edit(Request $request)
    {
        try {
            $result       = $this->noteService->where([
                'id' => $request->note_id,
                'company_id' => auth()->user()->company_id,
            ])->first();
            if (@$result) {
                $data['title']    = _trans('project.Update Note');
                $data['edit']     = $result;
                $data['url']      = (hasPermission('task_notes_update')) ? route('task.note.update', $request->note_id . '?task_id=' . $request->task_id) : '';
                $data['button']   = _trans('common.Update');
                return view('backend.project.note.createModal', compact('data'));
            } else {
                return response()->json('fail');
            }
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $result = $this->noteService->update($request, $id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->back();
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
            $result = $this->noteService->delete($id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('task.view', [$result->original['data']->task_id, 'notes']);
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }
}
