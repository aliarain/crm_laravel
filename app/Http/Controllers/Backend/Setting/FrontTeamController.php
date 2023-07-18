<?php

namespace App\Http\Controllers\Backend\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\Setting\TeamMemberRequest;
use App\Repositories\Settings\FrontTeamRepository;

class FrontTeamController extends Controller
{
    protected $service;
    public function __construct(FrontTeamRepository $service)
    {
        $this->service = $service;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->service->table($request);
        }
        $data['title'] = _trans('common.Team Member List');
        $data['checkbox'] = true;
        $data['table']     = route('team-member.index');
        $data['url_id']    = 'team_member_table_url';
        $data['fields']    = $this->service->fields();
        $data['class']     = 'table_class';
        $data['delete_url'] =  route('team-member.delete_data');
        $data['status_url'] =  route('team-member.statusUpdate');

        return view('backend.settings.team.index', compact('data'));
    }

    public function create()
    {
        $data['title']    = _trans('common.Create Team Member');
        $data['url']      = (hasPermission('team_member_store')) ? route('team-member.store') : '';
        $data['button']   = _trans('common.Save');
        return view('backend.settings.team.create', compact('data'));
    }

    public function store(TeamMemberRequest $request)
    {
        try {
            $result = $this->service->store($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('team-member.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data['edit']     = $this->service->find($id);
        $data['title']    = _trans('common.Edit Team Member');
        $data['url']      = (hasPermission('team_member_update')) ? route('team-member.update', $id) : '';
        $data['button']   = _trans('common.Update');
        return view('backend.settings.team.edit', compact('data'));
    }

    public function update(TeamMemberRequest $request, $id)
    {
        try {
            $result = $this->service->update($request, $id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('team-member.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function delete($id){
        try {
            $result = $this->service->delete($id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('team-member.index');
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
