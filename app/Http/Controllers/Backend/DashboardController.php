<?php

namespace App\Http\Controllers\Backend;

use App\Models\SearchMenu;
use Illuminate\Http\Request;
use App\Services\Task\TaskService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Repositories\DashboardRepository;
use App\Services\Management\ProjectService;
use App\Repositories\Hrm\Payroll\SalaryRepository;
use App\Repositories\Hrm\Finance\ExpenseRepository;
use App\Repositories\Hrm\Expense\HrmExpenseRepository;
use App\Repositories\Hrm\Attendance\AttendanceRepository;
use App\Repositories\Hrm\Department\DepartmentRepository;


class DashboardController extends Controller
{
    protected $dashboardRepository;
    protected $attendanceRepo;
    protected $expenseRepo;
    protected $expenseNewRepo;
    protected $departmentRepo;
    protected $salaryRepository;
    protected $taskService;
    protected $projectService;



    public function __construct(
    DashboardRepository $dashboardRepository, 
    AttendanceRepository $attendanceRepo, 
    HrmExpenseRepository $expenseRepo,
    ExpenseRepository $expenseNewRepo,
    DepartmentRepository $departmentRepo,
    SalaryRepository $salaryRepository,
    TaskService $taskService,
    ProjectService $projectService
    )
    {
        $this->dashboardRepository = $dashboardRepository;
        $this->attendanceRepo = $attendanceRepo;
        $this->expenseRepo = $expenseRepo;
        $this->expenseNewRepo = $expenseNewRepo;
        $this->departmentRepo = $departmentRepo;
        $this->salaryRepository = $salaryRepository;
        $this->taskService = $taskService;
        $this->projectService = $projectService;
    }

    public function loadMyProfileDashboard($request)
    {
        try {
            
            $request['month'] = date('Y-m');

            $menus = $this->dashboardRepository->getNewDashboardStatistics($request);
            $data['dashboardMenus'] = @$menus->original['data'];
            
            return $returnHTML = view('backend.dashboard.load_my_dashboard', compact('data'))->render();
            return $returnHTML = view('backend.dashboard.loadProfileDashboard', compact('data'))->render();
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function loadCompanyDashboard($request)
    {
        try {
            $request['month'] = date('Y-m');

            $menus = $this->dashboardRepository->getNewCompanyDashboardStatistics($request);
            $data['dashboardMenus'] = @$menus->original['data'];
            
            return $returnHTML = view('backend.dashboard.load_company_dashboard', compact('data'))->render();
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function loadSuperadminDashboard($request)
    {
        try {
            $request['month'] = date('Y-m');
            $menus = $this->dashboardRepository->getDashboardStatistics($request);
            $data['dashboardMenus'] = @$menus->original['data'];
            $companyMenus = $this->dashboardRepository->getCompanyDashboardStatistics($request);
            $data['companyMenus'] = @$companyMenus->original['data'];
            return $returnHTML = view('backend.dashboard.loadSuperadminDashboard', compact('data'))->render();
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }



    public function profileWiseDashboard(Request $request)
    {


        $user = Auth::user();
        $dashboard = '';
        $date=date('Y-m-d');
        if($request->dashboardType == null){
            $request['dashboardType'] = "Company Dashboard";
        }
        switch ($request->dashboardType) {
    
            case 'My Dashboard':
                $dashboard = $this->loadMyProfileDashboard($request);
                $data['dashboardType'] = _trans('dashboard.Dashboard');
                $data['project']            = $this->dashboardRepository->getProjectDashboard(auth()->id());
                $data['task']               = $this->dashboardRepository->getTaskDashboard(auth()->id());     
                $data['appointment']        = $this->dashboardRepository->getAppointmentDashboard(auth()->id());    
                $data['meeting']            = $this->dashboardRepository->getMeetingDashboard(auth()->id());   
                break;
            case 'Company Dashboard':
                $dashboard = $this->loadCompanyDashboard($request);
                $data['dashboardType'] = _trans('dashboard.Company Dashboard');
                $data['expense']            = $this->expenseNewRepo->getMonthlyExpense($request);
                $data['attendance_summary'] = $this->attendanceRepo->getTodayAttendanceDashboard($date);
                $data['payroll']            = $this->salaryRepository->getMonthlyPayroll();  
                $data['project']            = $this->dashboardRepository->getProjectDashboard();
                $data['task']               = $this->dashboardRepository->getTaskDashboard();        

                break;
            case 'Superadmin Dashboard':
                $dashboard = $this->loadSuperadminDashboard($request);
                $data['dashboardType'] = 'Super Admin';
                break;
        }
        $data['dashboard'] = $dashboard;
        $data['status'] = 'success';
        $data['message'] = 'Dashboard loaded successfully';
        return $data;
    }

    public function companyDashboard()
    {
        $request['month'] = date('Y-m');
        $menus = $this->dashboardRepository->getDashboardStatistics($request);
        $data['dashboardMenus'] = @$menus->original['data'];
        $companyMenus = $this->dashboardRepository->getCompanyDashboardStatistics($request);
        $data['companyMenus'] = @$companyMenus->original['data'];
        $attendance_data = $this->attendanceRepo->getCheckInCheckoutStatus(auth()->user()->id);
        $data['attendance'] = @$attendance_data->original['data'];
        $company_today_attendance = $this->attendanceRepo->getTodayAttendance(date('Y-m-d'));
        $data['company_today_attendance'] = $company_today_attendance;

        return $data;
    }

    public function superadminDashboard()
    {
        $request['month'] = date('Y-m');
        $menus = $this->dashboardRepository->getSuperadminDashboardStatistic($request);
        $data['dashboardMenus'] = @$menus->original['data'];


        return $data;
    }

    public function index(Request $request)
    {
        
        try {
            if (auth()->user()->role_id == 1) {
                $data = $this->superadminDashboard();
                return view('backend.__superadmin_dashboard', compact('data'));
            } else {
                if (!config('settings.app')['site_under_maintenance']) {
                    
                    $data = $this->companyDashboard();
                    return view('backend.dashboard', compact('data'));
                } else {
                    return redirect('/');
                }
            }
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }


    public function currentMonthPieChart(Request $request)
    {
        $request['month'] = date('Y-m');
        return $this->expenseRepo->getMonthlyExpense();
    }

    public function incomeExpenseGraph(Request $request)
    {
        try {
            return $this->dashboardRepository->getIncomeExpenseGraph($request);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function statistics(Request $request)
    {
        try {
            return $this->dashboardRepository->getDashboardStatistics($request);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('adminLogin');
    }

    // search
    public function search(Request $request){
        try {
            $search = SearchMenu::query();
            if($request->has('search')){
                $search->where('title','like','%'.$request->search.'%');
            }
            $search = $search->take(10)->get()->map(function($item){
                return [ 
                    'title' => $item->title,
                    'route_name' =>  route($item->route_name)
                ];
            });
            return response()->json($search);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}