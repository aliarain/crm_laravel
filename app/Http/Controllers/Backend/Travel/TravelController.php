<?php

namespace App\Http\Controllers\Backend\Travel;

use App\Models\Award\Award;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Award\AwardService;
use Brian2694\Toastr\Facades\Toastr;
use App\Services\Travel\TravelService;
use App\Http\Requests\StoreAwardRequest;
use App\Http\Requests\TravelStoreRequest;
use App\Http\Requests\UpdateAwardRequest;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class TravelController extends Controller
{
    use ApiReturnFormatTrait;

    protected $service;

    public function __construct(TravelService $service)
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
            $data['title']     = _trans('travel.Travel List');
            $data['table']     = route('travel.table');
            $data['url_id']    = 'travel_table_url';
            $data['fields']    = $this->service->fields();
            $data['class']     = 'travel_table_class';
            return view('backend.travel.index', compact('data'));
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
            $data['title']     = _trans('travel.Create Travel');
            $data['url']      = (hasPermission('travel_store')) ? route('travel.store') : '';
            $data['types']  = dbTable('travel_types', ['name', 'id'], ['company_id' => auth()->user()->company_id])->get();
            return view('backend.travel.create', compact('data'));
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
    public function store(TravelStoreRequest $request)
    {
        try {
            $result = $this->service->store($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('travel.index');
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
            $data['title']     = _trans('award.View Travel');
            $data['view']      = $this->service->where([
                'id' => $id,
                'company_id' => auth()->user()->company_id
            ])->first();
            if (!$data['view']) {
                Toastr::error(_trans('response.Travel not found.'), 'Error');
                return redirect()->back();
            }
            return view('backend.travel.view', compact('data'));
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
            $data['title']     = _trans('travel.Edit Travel');
            $data['edit']      = $this->service->where([
                'id' => $id,
                'company_id' => auth()->user()->company_id
            ])->first();
            if (!$data['edit']) {
                Toastr::error(_trans('response.Travel not found.'), 'Error');
                return redirect()->back();
            }
            $data['url']      = (hasPermission('travel_update')) ? route('travel.update', $id) : '';
            $data['travel_types']  = dbTable('travel_types', ['name', 'id'], ['company_id' => auth()->user()->company_id])->get();
            return view('backend.travel.edit', compact('data'));
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
    public function update(TravelStoreRequest $request, $id)
    {
        try {
            $result = $this->service->update($request, $id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('travel.index');
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
            $result = $this->service->delete($id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('travel.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    function approve(Request $request, $id)
    {
        try {
            $params                = [
                'id' => $id,
                'company_id' => auth()->user()->company_id,
            ];
            if (!is_Admin()) {
                $params['user_id'] = auth()->user()->id;
            }
            $data['travel']      = $this->service->where($params)->first();
            if (!$data['travel']) {
                Toastr::error(_trans('response.Travel not found.'), 'Error');
                return redirect()->back();
            }
            $data['url']           = (hasPermission('travel_approve')) ? route('travel.approve_store', $id) : '';
            $data['button']        = _trans('common.Approve');
            $data['title']     = _trans('travel.Travel Approve');
            return view('backend.travel.approve_modal', compact('data'));
        } catch (\Throwable $e) {
            return response()->json('fail');
        }
    }
    function approve_store(Request $request, $id)
    {
        try {
            $result = $this->service->approve($request, $id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('travel.index');
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


    public function userProfileTable(Request $request)
    {
        if ($request->ajax()) {
            return $this->service->table($request);
        }
    }
}
