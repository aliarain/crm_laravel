<?php

namespace App\Http\Controllers\Backend\Performance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Performance\Indicator;
use App\Models\Performance\CompetenceType;
use App\Http\Requests\UpdateIndicatorRequest;
use App\Services\Performance\IndicatorService;
use App\Http\Requests\Performance\IndicatorRequest;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Repositories\Hrm\Department\DepartmentRepository;

class IndicatorController extends Controller
{
    use ApiReturnFormatTrait;

    protected $service;
    protected $departmentRepository;
    public function __construct(IndicatorService $service, DepartmentRepository $departmentRepository)
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
            $data['title']     = _trans('performance.Indicator List');
            $data['table']     = route('performance.indicator.table');
            $data['url_id']    = 'indicator_table_url';
            $data['fields']    = $this->service->fields();
            $data['class']     = 'indicator_table_class';
            $data['departments'] = $this->departmentRepository->getAll();
            return view('backend.performance.indicator.index', compact('data'));
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
            $data['title']         = _trans('performance.Create Indicator');
            $data['url']           = (hasPermission('performance_indicator_store')) ? route('performance.indicator.store') : '';
            $data['competence_types']   = CompetenceType::with('competencies')->where(['company_id' => auth()->user()->company_id])->get();
            $data['designations']  = dbTable('designations', ['title', 'id'], ['company_id' => auth()->user()->company_id])->get();
            $data['shifts']        = dbTable('shifts', ['name', 'id'], ['company_id' => auth()->user()->company_id])->get();
            $data['departments']   = dbTable('departments', ['title', 'id'], ['company_id' => auth()->user()->company_id])->get();
            @$data['button']   = _trans('common.Save');
            return view('backend.performance.indicator.createModal', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }


    public function store(IndicatorRequest $request)
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
            $data['title']              = _trans('performance.View Indicator');
            $data['competence_types']   = CompetenceType::with('competencies')->where(['company_id' => auth()->user()->company_id])->get();
            return view('backend.performance.indicator.viewModal', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Performance\Indicator  $indicator
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $data['edit']               = $this->service->find($id);
            $data['title']              = _trans('performance.Edit Indicator');
            $data['url']                = (hasPermission('performance_indicator_update')) ? route('performance.indicator.update', $id) : '';
            $data['competence_types']   = CompetenceType::with('competencies')->where(['company_id' => auth()->user()->company_id])->get();
            $data['designations']       = dbTable('designations', ['title', 'id'], ['company_id' => auth()->user()->company_id])->get();
            $data['shifts']             = dbTable('shifts', ['name', 'id'], ['company_id' => auth()->user()->company_id])->get();
            $data['departments']        = dbTable('departments', ['title', 'id'], ['company_id' => auth()->user()->company_id])->get();
            @$data['button']            = _trans('common.Update');
            return view('backend.performance.indicator.editModal', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }


    public function update(IndicatorRequest $request, $id)
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
                return redirect()->route('performance.indicator.index');
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
