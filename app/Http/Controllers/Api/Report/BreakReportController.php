<?php

namespace App\Http\Controllers\Api\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Services\Hrm\EmployeeBreakService;
use App\Repositories\Report\BreakReportRepository;

class BreakReportController extends Controller
{
    protected $breakReport;
    protected $users;
    protected $breakBackService;

    public function __construct(BreakReportRepository $breakReport, UserRepository $users,EmployeeBreakService $breakBackService)
    {
        $this->breakReport = $breakReport;
        $this->users = $users;
        $this->breakBackService = $breakBackService;
    }
    public function dateSummary(Request $request)
    {
        return $this->breakReport->dateSummary($request);
    }
    public function userBreakHistory(Request $request)
    {
        return $this->breakBackService->userBreakHistory($request);
    }
}
