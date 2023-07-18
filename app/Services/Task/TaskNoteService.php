<?php

namespace App\Services\Task;

use App\Models\Management\Notes;
use App\Services\Core\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\TaskManagement\TaskNote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\CurrencyTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\InvoiceGenerateTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class TaskNoteService extends BaseService
{
    use RelationshipTrait, DateHandler, InvoiceGenerateTrait, CurrencyTrait, ApiReturnFormatTrait;

    public function __construct(TaskNote $notes)
    {
        $this->model = $notes;
    }

    // store the
    public function store($request)
    {

        $validator = Validator::make(\request()->all(), [
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('Description field is required'), 'id', 404);
        }
        DB::beginTransaction();
        try {
            $task = DB::table('tasks')->where('id', $request->task_id)->first();
            if (!$task) {
                return $this->responseWithError(_trans('message.Task not found'), [], 400);
            }
            $data = [
                'company_id' => auth()->user()->company_id,
                'task_id' => $request->task_id,
                'description' => $request->description,
                'user_id' => auth()->user()->id,
                'show_to_customer' => @$request->show_to_customer == 1  ? 33 : 22,
                'last_activity' => date('Y-m-d H:i:s'),
            ];
            $note = $this->model->create($data);            
            \App\Models\TaskManagement\TaskActivity::CreateActivityLog(auth()->user()->company_id, $request->task_id, auth()->id(), 'Created Notes')->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Note created successfully.'), $note);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    function update($request, $id)
    {

        $validator = Validator::make(\request()->all(), [
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('message.Description field is required'), 'id', 404);
        }
        DB::beginTransaction();
        try {
            $note = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
            if (!$note) {
                return $this->responseWithError(_trans('message.Note not found'), 'id', 404);
            }
            $note->description       = $request->description;
            // $note->show_to_customer  = @$request->show_to_customer == 1  ? 33 : 22;
            $note->last_activity     = date('Y-m-d H:i:s', time());
            $note->save();            
            \App\Models\TaskManagement\TaskActivity::CreateActivityLog(auth()->user()->company_id, $note->task_id, auth()->id(), 'Updated Notes')->save();
            DB::commit();
            return $this->responseWithSuccess(_trans('message.Note Updated successfully.'), $note);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    function delete($id)
    {
        $note = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
        if (!$note) {
            return $this->responseWithError(_trans('message.Note not found'), 'id', 404);
        }
        try {
            \App\Models\TaskManagement\TaskActivity::CreateActivityLog(auth()->user()->company_id, $note->task_id, auth()->id(), 'Deleted Notes')->save();
            $note->delete();            
            return $this->responseWithSuccess(_trans('message.Note Delete successfully.'), $note);
        } catch (\Throwable $th) {
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }
}
