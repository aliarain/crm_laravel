<?php

namespace App\Http\Controllers\Backend\Performance;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Performance\Appraisal;
use App\Models\Performance\CompetenceType;
use App\Services\Performance\AppraisalService;
use App\Http\Requests\Performance\AppraisalRequest;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Repositories\Hrm\Department\DepartmentRepository;

class AppraisalController extends Controller
{
    use ApiReturnFormatTrait;

    protected $service;
    protected $departmentRepository;
    public function __construct(AppraisalService $service, DepartmentRepository $departmentRepository)
    {
        $this->service = $service;
        $this->departmentRepository = $departmentRepository;
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
            $data['title']     = _trans('performance.Appraisal List');
            $data['table']     = route('performance.appraisal.table');
            $data['url_id']    = 'appraisal_table_url';
            $data['fields']    = $this->service->fields();
            $data['class']     = 'appraisal_table_class';
            $data['departments'] = $this->departmentRepository->getAll();
            return view('backend.performance.appraisal.index', compact('data'));
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
            $data['title']         = _trans('performance.Create Appraisal');
            $data['url']           = (hasPermission('performance_appraisal_store')) ? route('performance.appraisal.store') : '';
            $data['competence_types']   = CompetenceType::with('competencies')->where(['company_id' => auth()->user()->company_id])->get();
            @$data['button']   = _trans('common.Save');
            $data['users']    = User::where('status_id', 1)->where('company_id', auth()->user()->company_id)->select('id', 'name')->get();
            return view('backend.performance.appraisal.createModal', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }


    public function store(AppraisalRequest $request)
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
            $data['title']              = _trans('performance.View Appraisal');
            $data['competence_types']   = CompetenceType::with('competencies')->where(['company_id' => auth()->user()->company_id])->get();
            return view('backend.performance.appraisal.viewModal', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Performance\Appraisal  $Appraisal
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $data['edit']               = $this->service->find($id);
            $data['title']              = _trans('performance.Edit Appraisal');
            $data['url']                = (hasPermission('performance_appraisal_update')) ? route('performance.appraisal.update', $id) : '';
            $data['competence_types']   = CompetenceType::with('competencies')->where(['company_id' => auth()->user()->company_id])->get();
            @$data['button']            = _trans('common.Update');
            $data['users']    = User::where('status_id', 1)->where('company_id', auth()->user()->company_id)->select('id', 'name')->get();
            return view('backend.performance.appraisal.editModal', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }


    public function update(AppraisalRequest $request, $id)
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
                return redirect()->route('performance.appraisal.index');
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
        return $this->service->destroyAll($request);
    }
}
