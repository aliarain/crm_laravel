<?php

namespace App\Http\Controllers\Backend\Performance;

use Illuminate\Http\Request;
use App\Models\Performance\Goal;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Services\Performance\GoalService;
use App\Models\Performance\CompetenceType;
use App\Http\Requests\Performance\GoalRequest;
use App\Http\Requests\Performance\GoalEditRequest;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class GoalController extends Controller
{
    use ApiReturnFormatTrait;
    
    protected $service;
    public function __construct(GoalService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data['checkbox'] = true;
            $data['title']     = _trans('performance.Goal List');
            $data['table']     = route('performance.goal.table');
            $data['url_id']    = 'goal_table_url';
            $data['fields']    = $this->service->fields();
            $data['class']     = 'goal_table_class';
            return view('backend.performance.goal.index', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function table(Request $request)
    {
        if ($request->ajax()) {
            return $this->service->table($request);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        try {
            $data['title']         = _trans('performance.Create Goal');
            $data['url']           = (hasPermission('performance_goal_store')) ? route('performance.goal.store') : '';
            $data['goal_types']             = dbTable('goal_types', ['name', 'id'], ['company_id' => auth()->user()->company_id])->get();
            @$data['button']   = _trans('common.Save');
            return view('backend.performance.goal.createModal', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

 
    public function store(GoalRequest $request)
    {
        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->service->store($request);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Performance\Indicator  $indicator
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        try {
            $data['edit']               = $this->service->find($id);
            $data['title']              = _trans('performance.View Goal');
            $data['competence_types']   = CompetenceType::with('competencies')->where(['company_id' => auth()->user()->company_id])->get();
            return view('backend.performance.appraisal.viewModal', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Performance\Goal  $Goal
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $data['edit']               = $this->service->find($id);
            $data['title']              = _trans('performance.Edit Goal');
            $data['url']                = (hasPermission('performance_goal_update')) ? route('performance.goal.update', $id) : '';
            $data['goal_types']             = dbTable('goal_types', ['name', 'id'], ['company_id' => auth()->user()->company_id])->get();
            @$data['button']            = _trans('common.Update');
            return view('backend.performance.goal.editModal', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }


    public function update(GoalEditRequest $request, $id)
    {
        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->service->update($request, $id);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Performance\Indicator  $indicator
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            $result = $this->service->delete($id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('performance.goal.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function getGoal(Request $request){
        try {
            return response()->json($this->service
            ->where([
                'company_id' => auth()->user()->company_id,
            ])->select('id', 'subject as name')
            ->where('subject', 'LIKE', $request->term . '%')->take(10)->get());
        } catch (\Throwable $th) {
            return 0;
        }
    }

      // destroy all selected data

      public function deleteData(Request $request)
      {
          if (demoCheck()) {
              return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
          }
          return $this->service->destroyAll($request);
      }
}
