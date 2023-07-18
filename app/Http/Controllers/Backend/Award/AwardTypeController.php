<?php

namespace App\Http\Controllers\Backend\Award;

use Illuminate\Http\Request;
use App\Models\Award\AwardType;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Services\Award\AwardTypeService;
use App\Http\Requests\StoreAwardTypeRequest;
use App\Http\Requests\UpdateAwardTypeRequest;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class AwardTypeController extends Controller
{
    use ApiReturnFormatTrait;

    protected $awardTypeService;

    public function __construct(AwardTypeService $awardTypeService)
    {
        $this->awardTypeService = $awardTypeService;
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
            $data['title']     = _trans('task.Award Type List');
            $data['table']     = route('award_type.table');
            $data['url_id']    = 'award_type_table_url';
            $data['fields']    = $this->awardTypeService->fields();
            $data['class']     = 'award_type_table_class';
            return view('backend.award.type.index', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function table(Request $request)
    {
        if ($request->ajax()) {
            return $this->awardTypeService->table($request);
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
            $data['title']     = _trans('award.Create Award Type');
            $data['url']       = (hasPermission('award_type_store')) ? route('award_type.store') : '';
            @$data['button']   = _trans('common.Save');
            return view('backend.award.type.createModal', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAwardTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAwardTypeRequest $request)
    {
        try {
            $result = $this->awardTypeService->store($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('award_type.index');
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
     * @param  \App\Models\Award\AwardType  $awardType
     * @return \Illuminate\Http\Response
     */
    public function show(AwardType $awardType)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Award\AwardType  $awardType
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $data['edit']      = $this->awardTypeService->where([
                'id' => $id,
                'company_id' => auth()->user()->company_id
            ])->first();
            if (blank($data['edit'])) {
                Toastr::error(_trans('validation.response.Data not found!'), 'Error');
                return redirect()->back();
            }
            $data['title']     = _trans('award.Update Award Type');
            $data['url']       = (hasPermission('award_type_update')) ? route('award_type.update', $data['edit']->id) : '';
            $data['button']   = _trans('common.Update');
            return view('backend.award.type.createModal', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAwardTypeRequest  $request
     * @param  \App\Models\Award\AwardType  $awardType
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAwardTypeRequest $request, $id)
    {
        try {
            $result = $this->awardTypeService->update($request, $id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('award_type.index');
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
     * @param  \App\Models\Award\AwardType  $awardType
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            $result = $this->awardTypeService->delete($id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('award_type.index');
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
        return $this->awardTypeService->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->awardTypeService->destroyAll($request);
    }
}
