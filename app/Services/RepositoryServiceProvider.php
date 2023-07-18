<?php

namespace App\Services;

use App\Repositories\Admin\RoleRepository;
use App\Repositories\DashboardRepository;
use App\Repositories\DutyScheduleRepository;
use App\Repositories\HolidayRepository;
use App\Repositories\Hrm\Appreciate\AppreciateRepository;
use App\Repositories\Hrm\Attendance\AttendanceRepository;
use App\Repositories\Hrm\Content\AllContentRepository;
use App\Repositories\Hrm\Employee\AppoinmentRepository;
use App\Repositories\Hrm\Expense\ExpenseCategoryRepository;
use App\Repositories\Hrm\Expense\HrmExpenseRepository;
use App\Repositories\Hrm\Leave\AssignLeaveRepository;
use App\Repositories\Hrm\Leave\LeaveRequestRepository;
use App\Repositories\Hrm\Leave\LeaveSettingRepository;
use App\Repositories\Hrm\Leave\LeaveTypeRepository;
use App\Repositories\Hrm\Meeting\MeetingRepository;
use App\Repositories\Hrm\Notice\NoticeRepository;
use App\Repositories\Hrm\Visit\VisitRepository;
use App\Repositories\Hrm\Visit\VisitScheduleRepository;
use App\Repositories\IncomeExpenseRepository;
use App\Repositories\Interfaces\AttendanceInterface;
use App\Repositories\PaymentRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\Report\BreakReportRepository;
use App\Repositories\Settings\AppSettingsRepository;
use App\Repositories\Settings\CompanyConfigRepository;
use App\Repositories\Settings\IpRepository;
use App\Repositories\Settings\ProfileUpdateSettingRepository;
use App\Repositories\Settings\SettingRepository;
use App\Repositories\Support\SupportTicketRepository;
use App\Repositories\WeekendsRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Team\TeamRepository;
use App\Repositories\Interfaces\TeamInterface;
use App\Repositories\Company\CompanyRepository;
use App\Repositories\Interfaces\PaymentInterface;
use App\Repositories\Interfaces\ProfileInterface;
use App\Repositories\Hrm\Department\DepartmentRepository;
use App\Repositories\Hrm\Designation\DesignationRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            ProfileInterface::class,
            ProfileRepository::class,
            PaymentInterface::class,
            PaymentRepository::class,
            DesignationRepository::class,
            DepartmentRepository::class,
            CompanyRepository::class,
            MeetingRepository::class,
            DutyScheduleRepository::class,
            DashboardRepository::class,
            HolidayRepository::class,
            HolidayRepository::class,
            IncomeExpenseRepository::class,
            WeekendsRepository::class,
            SupportTicketRepository::class,
            AppSettingsRepository::class,
            CompanyConfigRepository::class,
            IpRepository::class,
            ProfileUpdateSettingRepository::class,
            SettingRepository::class,
            BreakReportRepository::class,
            VisitRepository::class,
            VisitScheduleRepository::class,
            NoticeRepository::class,
            AssignLeaveRepository::class,
            LeaveRequestRepository::class,
            LeaveSettingRepository::class,
            LeaveTypeRepository::class,
            ExpenseCategoryRepository::class,
            HrmExpenseRepository::class,
            AppoinmentRepository::class,
            DesignationRepository::class,
            DepartmentRepository::class,
            AllContentRepository::class,
            AppreciateRepository::class,
            CompanyRepository::class,
            RoleRepository::class,
            TeamRepository::class,
            TeamInterface::class,
            AttendanceRepository::class,
            AttendanceInterface::class

        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
