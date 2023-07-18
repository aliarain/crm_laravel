<?php

namespace App\Http\Controllers\Backend\Management;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Services\Management\ProjectService;
use App\Http\Requests\Project\CommentRequest;
use App\Services\Management\DiscussionService;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class DiscussionController extends Controller
{
    use ApiReturnFormatTrait;
    protected $discussionService;
    protected $projectService;

    public function __construct(DiscussionService $discussionService, ProjectService $projectService)
    {
        $this->discussionService = $discussionService;
        $this->projectService    = $projectService;
    }

    // discussion datatable 
    public function datatable(Request $request, $id)
    {
        return $this->discussionService->discussionDatatable($request, $id);
    }

    public function create(Request $request)
    {
        try {
            $result       = $this->projectService->where([
                'id' => $request->project_id,
                'company_id' => auth()->user()->company_id,
            ])->first();
            if (@$result) {
                $data['title']    = _trans('project.Create Discussion');
                $data['view'] = $result;
                $data['url']      = (hasPermission('discussion_store')) ? route('project.discussion.store', 'project_id=' . $request->project_id) : '';
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
            return $this->responseExceptionError($th->getMessage(), [], 400);
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
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }
    public function delete($id)
    {
        try {
            $result = $this->discussionService->delete($id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('project.view', [$result->original['data']->project_id, 'discussions']);
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
