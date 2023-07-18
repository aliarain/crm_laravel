<?php

namespace App\Http\Controllers\Api\Report\Attendance;

use App\Helpers\CoreApp\Traits\DateHandler;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Report\AttendanceReportRepository;
use Illuminate\Http\Request;

class AttendanceReportController extends Controller
{
    use DateHandler;

    protected AttendanceReportRepository $attendanceRepository;

    public function __construct(AttendanceReportRepository $attendanceRepository)
    {
        $this->attendanceRepository = $attendanceRepository;
    }

    public function userMonthlyAttendanceReport(Request $request, User $user): \Illuminate\Http\JsonResponse
    {
        return $this->attendanceRepository->userAttendanceReport($user, $request);
    }
    public function userDailyAttendanceReport(Request $request)
    {
        return $this->attendanceRepository->userDailyAttendanceReport($request);
    }
    public function dateSummary(Request $request)
    {
        return $this->attendanceRepository->dateSummaryReport($request);
    }
    public function summaryToList(Request $request)
    {
        return $this->attendanceRepository->summaryToListReport($request);
    }
}
