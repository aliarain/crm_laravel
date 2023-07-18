<?php

namespace App\Http\Controllers\Frontend\Employee;

use App\Models\User;
use App\Repositories\DashboardRepository;
use App\Repositories\Settings\AppSettingsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    protected $settings;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->settings = $dashboardRepository;
    }

    public function employeeDashboard(Request $request)
    {
        $data['title'] = _trans('common.Employee Dashboard');
        $data['user'] = User::find(5);
        $request['month'] = date('Y-m');
        $menus = $this->settings->getDashboardStatistics($request);
        $data['dashboardMenus'] = $menus->original['data'];
        return view('frontend.dashboard.index', compact('data'));
    }
}
