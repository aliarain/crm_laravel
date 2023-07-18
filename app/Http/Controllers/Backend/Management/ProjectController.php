<?php

namespace App\Http\Controllers\Backend\Management;

use Illuminate\Http\Request;
use App\Models\Management\Project;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\ProjectRequest;
use App\Services\Management\ProjectService;
use App\Repositories\Hrm\Finance\AccountRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Repositories\Hrm\Expense\ExpenseCategoryRepository; 

class ProjectController extends Controller
{
    use ApiReturnFormatTrait;
    protected $projectService;
    protected $accountRepository;
    protected $incomeExpenseCategoryRepository;

    public function __construct(ProjectService $projectService, AccountRepository $accountRepository, ExpenseCategoryRepository $incomeExpenseCategoryRepository)
    {
        $this->projectService = $projectService;
        $this->accountRepository = $accountRepository;
        $this->incomeExpenseCategoryRepository = $incomeExpenseCategoryRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data['title']     = _trans('project.Projects List');
            if ($request->ajax()) {
                return $this->projectService->table($request);
            }
            $data['fields']    = $this->projectService->fields();
            $data['checkbox'] = true;

            $data['table']     = route('project.index');
            $data['url_id']    = 'project_table_url';
            $data['class']     = 'table_class';

            $data['status_url'] = route('hrm.project.statusUpdate');
            $data['delete_url'] = route('hrm.project.deleteData');

            return view('backend.project.index', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function datatable(Request $request)
    {
        return $this->projectService->datatable($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $data['title']    = _trans('project.Projects Create');
            $data['url']      = (hasPermission('project_store')) ? route('project.store') : '';
            $data['clients']  = dbTable('clients', ['name', 'id', 'deleted_at'], ['company_id' => auth()->user()->company_id])->where('deleted_at', null)->get();
            return view('backend.project.create', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        
        
        try {
            $result = $this->projectService->store($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('project.index');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function task_table(Request $request, $id)
    {
        if ($request->ajax()) {
            return $this->projectService->task_table($request, $id);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $params                = [
                'id' => $id,
                'company_id' => auth()->user()->company_id,
            ];
            $data['edit']       = $this->projectService->where($params)->with('members')->first();
            if (!$data['edit']) {
                Toastr::error('Project not found!', 'Error');
                return redirect()->back();
            }
            $data['title']    = _trans('project.Projects Edit');
            $data['url']      = (hasPermission('project_update')) ? route('project.update', [$data['edit']->id]) : '';
            $data['clients']  = dbTable('clients', ['name', 'id'], ['company_id' => auth()->user()->company_id])->get();
            return view('backend.project.edit', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, $id)
    {
        try {
            $result = $this->projectService->update($request, $id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('project.index');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            $result = $this->projectService->delete($id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('project.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }
    public function member_delete(Request $request, $id)
    {
        try {
            $result = $this->projectService->member_delete($request, $id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('project.view', [$request->project_id, 'members']);
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function view(Request $request, $id, $slug)
    {
        try {
            $result       = $this->projectService->view($id, $slug, $request);
            
            if ($result->original['result']) {
                $title    = _trans('project.Project View');
                $data = $result->original['data'];
                return view('backend.project.view', compact('data', 'title'));
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function complete(Request $request)
    {
        try {
            $result = $this->projectService->status($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('project.view', [$request->project_id, 'overview']);
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }


    // payment methods

    public function payment_modal($id)
    {
        try {
            $data['title']         = _trans('payroll.Make Payment');
            $params                = [
                'id' => $id,
                'company_id' => auth()->user()->company_id,
            ];

            $data['project']       = $this->projectService->where($params)->first();

            $data['category'] = $this->incomeExpenseCategoryRepository->model(
                [
                    'is_income' => 1,
                    'status_id' => 1,
                    'company_id' => auth()->user()->company_id,
                ]
            )->get();
            $data['payment_method'] = DB::table('payment_methods')->where(
                [
                    'company_id' => auth()->user()->company_id,
                ]
            )->get();
            $data['url']           = (hasPermission('project_payment')) ? route('project.payment', $id) : '';
            if (!is_Admin()) {
                $data['url'] = '';
            }
            $data['accounts']      = $this->accountRepository->model(
                [
                    'company_id' => auth()->user()->company_id,
                    'status_id' => 1,
                ]
            )->get();
            return view('backend.project.payment_modal', compact('data'));
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    function payment(Request $request, $id)
    {
        if (!$request->category) {
            Toastr::error('Please select category!', 'Error');
            return redirect()->back();
        }
        if (!$request->account) {
            Toastr::error('Please select account!', 'Error');
            return redirect()->back();
        }
        try {
            $params                = [
                'id' => $id,
                'company_id' => auth()->user()->company_id,
            ];
            $project       = $this->projectService->where($params)->first();
            if (!$project) {
                Toastr::error('Project not found!', 'Error');
                return redirect()->route('project.index');
            }
            $result = $this->projectService->pay($request, $project);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('project.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    function invoice($id)
    {

        try {
            $params                = [
                'id' => $id,
                'company_id' => auth()->user()->company_id,
            ];
            if (!is_Admin()) {
                $params['client_id'] = auth()->user()->id;
            }
            $data['title'] = _trans('common.Invoice');
            $data['project']       = $this->projectService->where($params)->first();
            if (!$data['project']) {
                Toastr::error('Project not found!', 'Error');
                return redirect()->route('project.index');
            }
            $pdf = \PDF::setOptions([
                'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                'logOutputFile' => storage_path('logs/log.htm'),
                'tempDir' => storage_path('logs/'),
            ])->loadView('backend.project.invoice', compact('data'));
            return $pdf->download('invoice-' . $data['project']->invoice . '.pdf');
        } catch (\Throwable $e) {
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
        return $this->projectService->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->projectService->destroyAll($request);
    }

    public function userProfileTable(Request $request)
    {
        if ($request->ajax()) {
            return $this->projectService->table($request);
        }
    }
}
