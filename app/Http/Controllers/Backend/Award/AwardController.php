<?php

namespace App\Http\Controllers\Backend\Award;

use App\Models\Award\Award;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Award\AwardService;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\StoreAwardRequest;
use App\Http\Requests\UpdateAwardRequest;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class AwardController extends Controller
{
    use ApiReturnFormatTrait;

    protected $awardService;

    public function __construct(AwardService $awardService)
    {
        $this->awardService = $awardService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data['fields']    = $this->awardService->fields();
            $data['checkbox'] = true;

            $data['title']     = _trans('award.Award List');
            $data['table']     = route('award.table');
            $data['url_id']    = 'award_table_url';
            $data['class']     = 'award_table_class';
            return view('backend.award.index', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function table(Request $request)
    {
        if ($request->ajax()) {
            return $this->awardService->table($request);
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
            $data['title']     = _trans('award.Create Award');
            $data['url']      = (hasPermission('award_store')) ? route('award.store') : '';
            $data['award_types']  = dbTable('award_types', ['name', 'id'], ['company_id' => auth()->user()->company_id])->get();
            return view('backend.award.create', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAwardRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAwardRequest $request)
    {
        try {
            $result = $this->awardService->store($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('award.index');
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
     * @param  \App\Models\Award\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        try {
            $data['title']     = _trans('award.View Award');
            $data['view']      = $this->awardService->where([
                'id' => $id,
                'company_id' => auth()->user()->company_id
            ])->first();
            if (!$data['view']) {
                Toastr::error(_trans('response.Award not found.'), 'Error');
                return redirect()->back();
            }
            return view('backend.award.view', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Award\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $data['title']     = _trans('award.Edit Award');
            $data['edit']      = $this->awardService->where([
                'id' => $id,
                'company_id' => auth()->user()->company_id
            ])->first();
            if (!$data['edit']) {
                Toastr::error(_trans('response.Award not found.'), 'Error');
                return redirect()->back();
            }
            $data['url']      = (hasPermission('award_update')) ? route('award.update', $id) : '';
            $data['award_types']  = dbTable('award_types', ['name', 'id'], ['company_id' => auth()->user()->company_id])->get();
            return view('backend.award.edit', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAwardRequest  $request
     * @param  \App\Models\Award\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAwardRequest $request, $id)
    {
        try {
            $result = $this->awardService->update($request, $id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('award.index');
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
     * @param  \App\Models\Award\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            $result = $this->awardService->delete($id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('award.index');
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
        return $this->awardService->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->awardService->destroyAll($request);
    }

    public function userProfileTable(Request $request)
    {
        if ($request->ajax()) {            
            return $this->awardService->table($request);
        }
    }
}
