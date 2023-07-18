<?php

namespace App\Services\Task;

use App\Services\Core\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Management\ProjectFile;
use App\Models\TaskManagement\TaskFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Helpers\CoreApp\Traits\CurrencyTrait;
use App\Models\Management\ProjectFileComment;
use App\Models\TaskManagement\TaskFileComment;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\InvoiceGenerateTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class TaskFileService extends BaseService
{
    use RelationshipTrait, DateHandler, FileHandler, InvoiceGenerateTrait, CurrencyTrait, ApiReturnFormatTrait;

    public function __construct(TaskFile $taskFile)
    {
        $this->model = $taskFile;
    }


    public function store($request)
    {

        DB::beginTransaction();
        try {
            $task = DB::table('tasks')->where('id', $request->task_id)->first();
            if (!$task) {
                return $this->responseWithError(_trans('message.Task not found'), [], 400);
            }

            $task_files = new $this->model;
            $task_files->company_id = auth()->user()->company_id;
            $task_files->task_id = $request->task_id;
            $task_files->subject = $request->subject;
            $task_files->user_id = auth()->user()->id;
            $task_files->show_to_customer = @$request->show_to_customer == 1  ? 33 : 22;
            $task_files->last_activity = date('Y-m-d H:i:s');
            if ($request->hasFile('attach_file')) {
                $task_files->attachment = $this->uploadImage($request->attach_file, 'task/files')->id;
            }
            $task_files->save();

            \App\Models\TaskManagement\TaskActivity::CreateActivityLog(auth()->user()->company_id, $request->task_id, auth()->id(), 'Created File')->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Project file created successfully.'), $task_files);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    function table($request, $id)
    {
        $files =  $this->model->where([
            'task_id' => $id,
            'company_id' => auth()->user()->company_id,
        ])->with('comments');

        if ($request->search) {
            $files = $files->where('subject', 'like', '%' . $request->search . '%');
        }
        if ($request->from && $request->to) {
            $files = $files->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        $files = $files->paginate($request->limit ?? 10);

        return [
            'data' => $files->map(function ($data) {
                $action_button = '';
                if (hasPermission('task_file_view')) {
                    $action_button .= '<a href="' . route('task.view', [$data->task_id, 'files', 'file_id=' . $data->id]) . '" class="dropdown-item"> <span class="icon mr-8"><i class="fa-solid fa-eye"></i></span>' . _trans('common.View') . '</a>';
                }
                if (hasPermission('task_file_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`admin/task/file/delete/`)', 'delete');
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
                    'date' => showDate($data->created_at),
                    'last_activity' => showDate($data->last_activity),
                    'comments' => $data->comments->count(),
                    'action'   => $button
                ];
            }),
            'pagination' => [
                'total' => $files->total(),
                'count' => $files->count(),
                'per_page' => $files->perPage(),
                'current_page' => $files->currentPage(),
                'total_pages' => $files->lastPage(),
                'pagination_html' =>  $files->links('backend.pagination.custom')->toHtml(),
            ],
        ];
    }

    // comment store the
    public function commentStore($request)
    {

        $validator = Validator::make(\request()->all(), [
            'comment' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('message.Required field missing'), $validator->errors(), 400);
        }
        DB::beginTransaction();
        try {
            $task_file = $this->model->where([
                'id' => $request->file_id,
                'company_id' => auth()->user()->company_id,
            ])->first();
            if (!$task_file) {
                return $this->responseWithError(_trans('message.File not found'), [], 400);
            }
            if ($request->comment_id == 0 || $request->comment_id == null) {
                $comment_id = null;
            }else {
                $comment_id = $request->comment_id;
            }
            $comment = new TaskFileComment();
            $comment->company_id = auth()->user()->company_id;
            $comment->task_file_id = $request->file_id;
            $comment->comment_id = $comment_id ?? null;
            $comment->show_to_customer = @$request->show_to_customer == 1  ? 33 : 22;
            $comment->description = $request->comment;
            $comment->user_id = auth()->user()->id;
            $comment->save();

            \App\Models\TaskManagement\TaskActivity::CreateActivityLog(auth()->user()->company_id, intval($task_file->task_id), auth()->id(), 'Created File Comments')->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Comment created successfully.'), $comment);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    function delete($id)
    {
        $file = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
        if (!$file) {
            return $this->responseWithError(_trans('File not found'), 'id', 404);
        }
        try {
            if (@$file->attachment) {
                $this->deleteImage(asset_path($file->attachment));
            }
            $file->comments()->delete();
            $file->delete();            
            \App\Models\TaskManagement\TaskActivity::CreateActivityLog(auth()->user()->company_id, $file->task_id, auth()->id(), 'Deleted File')->save();
            return $this->responseWithSuccess(_trans('message.File Delete successfully.'), $file);
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
                    if (@$file->attachment) {
                        $this->deleteImage(asset_path($file->attachment));
                    }
                    $file->comments()->delete();
                    $file->delete(); 
                }
                return $this->responseWithSuccess(_trans('message.Task File delete successfully.'), $category);
            } else {
                return $this->responseWithError(_trans('message.Task File not found'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
