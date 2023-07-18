<?php

use Illuminate\Database\Seeder;
use Database\Seeders\GoalSeeder;
use Database\Seeders\AwardSeeder;
use Database\Seeders\LeaveSeeder;
use Database\Seeders\NoticeSeeder;
use Database\Seeders\UploadSeeder;
use Database\Seeders\ExpenseSeeder;
use Database\Seeders\FeatureSeeder;
use Database\Seeders\PayrollSeeder;
use Database\Seeders\Hrm\TeamSeeder;
use Database\Seeders\PurchaseSeeder;
use Database\Seeders\SettingsSeeder;
use Database\Seeders\IndicatorSeeder;
use Database\Seeders\StockSaleSeeder;
use Database\Seeders\Task\TaskSeeder;
use Database\Seeders\Admin\RoleSeeder;
use Database\Seeders\Admin\UserSeeder;
use Database\Seeders\AttendanceSeeder;
use Database\Seeders\Leads\LeadSeeder;
use Database\Seeders\SearchMenuSeeder;
use Database\Seeders\StockBrandSeeder;
use Database\Seeders\DesignationSeeder;
use Database\Seeders\Hrm\HolidaySeeder;
use Database\Seeders\Hrm\MeetingSeeder;
use Database\Seeders\TestimonialSeeder;
use Database\Seeders\TransactionSeeder;
use Database\Seeders\CrmInstallerSeeder;
use Database\Seeders\DutyScheduleSeeder;
use Database\Seeders\StockHistorySeeder;
use Database\Seeders\StockProductSeeder;
use Database\Seeders\WeekendSetupSeeder;
use Database\Seeders\Admin\CompanySeeder;
use Database\Seeders\CompanyConfigSeeder;
use Database\Seeders\StockCategorySeeder;
use Database\Seeders\Travel\TravelSeeder;
use Database\Seeders\Frontend\FrontSeeder;
use Database\Seeders\Hrm\AllContentSeeder;
use Database\Seeders\Hrm\Visit\NoteSeeder;
use Database\Seeders\Leads\LeadTypeSeeder;
use Database\Seeders\Hrm\AppointmentSeeder;
use Database\Seeders\Hrm\Shift\ShiftSeeder;
use Database\Seeders\Hrm\Visit\VisitSeeder;
use Database\Seeders\Admin\PermissionSeeder;
use Database\Seeders\Hrm\LeaveSettingSeeder;
use Database\Seeders\Hrm\SubscriptionSeeder;
use Database\Seeders\Leads\LeadSourceSeeder;
use Database\Seeders\Leads\LeadStatusSeeder;
use Database\Seeders\NotificationTypeSeeder;
use Database\Seeders\StockSaleHistorySeeder;
use Database\Seeders\Hrm\EmployeeTasksSeeder;
use Database\Seeders\Management\ClientSeeder;
use Database\Seeders\Hrm\Visit\ScheduleSeeder;
use Database\Seeders\Management\ProjectSeeder;
use Database\Seeders\Hrm\Country\CountrySeeder;
use Database\Seeders\StockPaymentHistorySeeder;
use Database\Seeders\ProductPurchaseHistorySeeder;
use Database\Seeders\Traits\ApplicationKeyGenerate;
use Database\Seeders\Hrm\AppSetting\AppScreenSeeder;
use Database\Seeders\Hrm\Notification\NotificationSeeder;

class DatabaseSeeder extends Seeder
{
    use ApplicationKeyGenerate;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        if (config('app.APP_CRM') && env('APP_ENV') == "local") {

            $this->keyGenerate();
            $this->call(CountrySeeder::class);
            $this->call(CompanySeeder::class);
            $this->call(DesignationSeeder::class);
            $this->call(PermissionSeeder::class);
            $this->call(RoleSeeder::class);
            $this->call(UploadSeeder::class);
            $this->call(ShiftSeeder::class);
            $this->call(WeekendSetupSeeder::class);
            $this->call(DutyScheduleSeeder::class);
            $this->call(UserSeeder::class);
            $this->call(SettingsSeeder::class);
            $this->call(LeaveSettingSeeder::class);
            $this->call(CompanyConfigSeeder::class);


            $this->call(ExpenseSeeder::class);
            $this->call(LeaveSeeder::class);
            $this->call(HolidaySeeder::class);
            $this->call(AppScreenSeeder::class);
            $this->call(AllContentSeeder::class);
            $this->call(FeatureSeeder::class);
            $this->call(TestimonialSeeder::class);
            $this->call(PayrollSeeder::class);
            $this->call(TeamSeeder::class);
            $this->call(NotificationTypeSeeder::class);

            //Demo Data Start
            $this->call(VisitSeeder::class);
            $this->call(NoteSeeder::class);
            $this->call(ScheduleSeeder::class);
            $this->call(NoticeSeeder::class);
            $this->call(EmployeeTasksSeeder::class);
            $this->call(AppointmentSeeder::class);
            $this->call(MeetingSeeder::class);
            $this->call(NotificationSeeder::class);
            $this->call(SubscriptionSeeder::class);
            $this->call(AttendanceSeeder::class);
            $this->call(ExpenseSeeder::class);

            $this->call(GoalSeeder::class);
            $this->call(ClientSeeder::class);
            $this->call(ProjectSeeder::class);
            $this->call(TaskSeeder::class);
            $this->call(AwardSeeder::class);
            $this->call(TravelSeeder::class);
            $this->call(IndicatorSeeder::class);
            $this->call(FrontSeeder::class);

            /* **************** stock seeder **************** */
            $this->call(StockCategorySeeder::class);
            $this->call(StockBrandSeeder::class);
            $this->call(StockProductSeeder::class);
            $this->call(StockPaymentHistorySeeder::class);
            $this->call(PurchaseSeeder::class);
            $this->call(ProductPurchaseHistorySeeder::class);
            $this->call(StockHistorySeeder::class);
            $this->call(StockSaleSeeder::class);
            $this->call(StockSaleHistorySeeder::class);
            /* **************** stock seeder **************** */

            //Income seeder
            $this->call(TransactionSeeder::class);

            // start lead module
            $this->call(LeadTypeSeeder::class);
            $this->call(LeadStatusSeeder::class);
            $this->call(LeadSourceSeeder::class);
            $this->call(LeadSeeder::class);
            // end lead module
            // search menu seeder
            $this->call(SearchMenuSeeder::class);
        } else {
            $this->call(CrmInstallerSeeder::class);
        }

    }
}
