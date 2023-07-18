<?php

namespace App\Services\Management;

use App\Services\Core\BaseService;
use Illuminate\Support\Facades\DB;
use App\Models\Management\Discussion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\CurrencyTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\InvoiceGenerateTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use Illuminate\Support\Facades\Log;

class DiscussionService extends BaseService
{
    use RelationshipTrait, DateHandler, InvoiceGenerateTrait, CurrencyTrait, ApiReturnFormatTrait;

    public function __construct(Discussion $discussion)
    {
        $this->model = $discussion;
    }

    //discussions datatable 

    function discussionDatatable($request, $id){
        $discussions =  $this->model->query()->with('comments')->where([
            'project_id' => $id,
            'company_id' => auth()->user()->company_id,
        ]);
        if ($request->search) {
            $discussions = $discussions->where('subject', 'like', '%' . $request->search . '%');
        }
        if ($request->from && $request->to) {
            $discussions = $discussions->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        $discussions = $discussions->paginate($request->limit ?? 10);

        return [
            'data' => $discussions->map(function ($data) {
                $action_button = '';
                if (hasPermission('discussion_view')) {
                    $action_button .= '<a href="' . route('project.view', [$data->project_id, 'discussions', 'discussion_id='.$data->id]) . '" class="dropdown-item"> <span class="icon mr-8"><i class="fa-solid fa-eye"></i></span>' . _trans('common.View') . '</a>';
                }
                if (hasPermission('discussion_delete')) {
                    $action_button .= actionButton( _trans('common.Delete'), '__globalDelete(' . $data->id . ',`admin/project/discussion/delete/`)', 'delete');
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
                    'id' => $data->id,
                    'subject' => $data->subject,
                    'last_activity' => showDate($data->last_activity),
                    'comments' => $data->comments->count(),
                    'date' => showDate($data->created_at),
                    'action'   => $button
                ];
            }),
            'pagination' => [
                'total' => $discussions->total(),
                'count' => $discussions->count(),
                'per_page' => $discussions->perPage(),
                'current_page' => $discussions->currentPage(),
                'total_pages' => $discussions->lastPage(),
                'pagination_html' =>  $discussions->links('backend.pagination.custom')->toHtml(),
            ],
        ];
    }

    // store the
    public function store($request)
    {
        
        $validator = Validator::make(\request()->all(), [
            'description' => 'required',
            'subject' => 'required|max:191',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Required field missing'), $validator->errors(), 400);
        }
        DB::beginTransaction();
        try {
            $project = DB::table('projects')->where('id', $request->project_id)->first();
            if (!$project) {
                return $this->responseWithError(_trans('validation.Project not found'), [], 400);
            }
            $data = [
                'company_id' => auth()->user()->company_id,
                'project_id' => $request->project_id,
                'subject' => $request->subject,
                'description' => $request->description,
                'user_id' => auth()->user()->id,
                'show_to_customer' => @$request->show_to_customer == 1  ? 33 : 22,
                'last_activity' => date('Y-m-d H:i:s'),
            ];
            $discussion = $this->model->create($data);            
            \App\Models\Management\ProjectActivity::CreateActivityLog(auth()->user()->company_id, $project->id, auth()->id(), 'Created Discussion')->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Discussion created successfully.'), $discussion);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }
    // comment store the
    public function commentStore($request)
    {
        
        
        $validator = Validator::make(\request()->all(), [
            'comment' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Required field missing'), $validator->errors(), 400);
        }
        DB::beginTransaction();
        try {
            $discussion = $this->model->where([
                'id' => $request->discussion_id,
                'company_id' => auth()->user()->company_id,
            ])->first();
            if (!$discussion) {
                return $this->responseWithError(_trans('validation.Discussion not found'), [], 400);
            }
            if ($request->comment_id == 0 || $request->comment_id == null) {
                $comment_id = Null;
            }else {
                $comment_id = $request->comment_id;
            }
            $comment = new \App\Models\Management\DiscussionComment;
            $comment->company_id = auth()->user()->company_id;
            $comment->discussion_id = $request->discussion_id;
            $comment->comment_id = $comment_id;
            $comment->description = $request->comment;
            $comment->user_id = auth()->user()->id;
            $comment->save();
            \App\Models\Management\ProjectActivity::CreateActivityLog(auth()->user()->company_id, $discussion->project_id, auth()->id(), 'Created Discussion Comments')->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Comment created successfully.'), $comment);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    function delete($id)
    {
        $discussion = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
        if (!$discussion) {
            return $this->responseWithError(_trans('Discussion not found'), 'id', 404);
        }
        try {
            $discussion->comments()->delete();
            $discussion->delete();            
            \App\Models\Management\ProjectActivity::CreateActivityLog(auth()->user()->company_id, $discussion->project_id, auth()->id(), 'Deleted Discussion')->save();
            return $this->responseWithSuccess(_trans('message.Discussion Delete successfully.'), $discussion);
        } catch (\Throwable $th) {
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $category = $this->model->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->get();
                foreach ($category as $file){
                    $file->comments()->delete();
                    $file->delete(); 
                }
                return $this->responseWithSuccess(_trans('message.Discussion delete successfully.'), $category);
            } else {
                return $this->responseWithError(_trans('message.Discussion not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}