<?php

namespace App\Http\Controllers\Backend\Performance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Performance\Appraisal;
use App\Http\Requests\StoreAppraisalRequest;
use App\Http\Requests\UpdateAppraisalRequest;
use App\Services\Performance\CompetenceService;
use App\Services\Performance\CompetenceTypeService;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class CompetenceController extends Controller
{
    use ApiReturnFormatTrait;

    protected $service;
    protected $typeService;
    public function __construct(CompetenceService $service, CompetenceTypeService $typeService)
    {
        $this->service = $service;
        $this->typeService = $typeService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data['title']     = _trans('performance.Competence List');
            $data['table']     = route('performance.competence.table');
            $data['url_id']    = 'competence_table_url';
            $data['fields']    = $this->service->fields();
            $data['class']     = 'competence_table_class';
            $data['class']     = 'table_class';
            $data['types']     = $this->typeService->select('id', 'name')->get();

            $data['checkbox'] = true;
            $data['status_url'] = route('performance.competence.statusUpdate');
            $data['delete_url'] = route('performance.competence.delete_data');
            return view('backend.performance.competence.index', compact('data'));
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
            $data['title']         = _trans('performance.Create Competence');
            $data['url']           = (hasPermission('performance_competence_store')) ? route('performance.competence.store') : '';
            $data['competence_types']             = dbTable('competence_types', ['name', 'id'], ['company_id' => auth()->user()->company_id])->get();
            @$data['button']   = _trans('common.Save');
            return view('backend.performance.competence.createModal', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }


    public function store(Request $request)
    {
        try {
            $result = $this->service->store($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('performance.competence.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
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
            $data['edit']      = $this->service->where([
                'id' => $id,
                'company_id' => auth()->user()->company_id
            ])->first();
            if (blank($data['edit'])) {
                Toastr::error(_trans('validation.response.Data not found!'), 'Error');
                return redirect()->back();
            }
            $data['title']         = _trans('performance.Create Competence');
            $data['url']           = (hasPermission('performance_competence_update')) ? route('performance.competence.update', $id) : '';
            $data['competence_types']             = dbTable('competence_types', ['name', 'id'], ['company_id' => auth()->user()->company_id])->get();
            @$data['button']   = _trans('common.Save');
            return view('backend.performance.competence.createModal', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $result = $this->service->update($request, $id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('performance.competence.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
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
                return redirect()->route('performance.competence.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    // status change
    public function statusUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        return $this->service->statusUpdate($request);
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
