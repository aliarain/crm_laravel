<?php

namespace App\Http\Controllers\Backend\Management;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Services\Management\NoteService;
use App\Services\Management\ProjectService;

class NoteController extends Controller
{
    protected $noteService;
    protected $projectService;

    public function __construct(NoteService $noteService, ProjectService $projectService)
    {
        $this->noteService = $noteService;
        $this->projectService    = $projectService;
    }

    public function create(Request $request)
    {
        try {
            $result       = $this->projectService->where([
                'id' => $request->project_id,
                'company_id' => auth()->user()->company_id,
            ])->first();
            if (@$result) {
                $data['title']    = _trans('project.Create Note');
                $data['view']     = $result;
                $data['url']      = (hasPermission('project_notes_store')) ? route('project.note.store', 'project_id=' . $request->project_id) : '';
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
                $data['url']      = (hasPermission('project_notes_update')) ? route('project.note.update', $request->note_id . '?project_id=' . $request->project_id) : '';
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
                return redirect()->route('project.view', [$result->original['data']->project_id, 'notes']);
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
