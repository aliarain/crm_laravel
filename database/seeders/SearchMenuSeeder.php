<?php

namespace Database\Seeders;

use App\Models\SearchMenu;
use Illuminate\Database\Seeder;

class SearchMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      
        $routes = [

            "meeting.create" => "Admin meeting create",
            "content.create" => "Admin content create",
            "menu.create" => "Admin menu create",
            "service.create" => "Admin service create",
            "portfolio.create" => "Admin portfolio create",
            "team-member.create" => "Admin team-member create",
            "travel.create" => "Admin travel create",
            "travel_type.create" => "Admin travel type create",
            "hrm.payroll_items.create" => "Hrm payroll item create",
            "hrm.payroll_advance_type.create" => "Hrm payroll advance-type create",
            "hrm.payroll_advance_salary.create" => "Hrm payroll advance-salary create",
            "language.create" => "Admin settings language-setup create",
            "user.create" => "Dashboard user create",
            "holidaySetup.create" => "Hrm holiday setup create",
            "dutySchedule.create" => "Hrm duty schedule create",
            "shift.create" => "Hrm shift create",
            "ipConfig.create" => "Hrm ip-whitelist create",
            "company.settings.locationCreate" => "Hrm location create",
            "task.create" => "Admin task create",
            "task.discussion.create" => "Admin task discussion create",
            "task.note.create" => "Admin task note create",
            "task.file.create" => "Admin task file create",
            "project.create" => "Admin project create",
            "project.discussion.create" => "Admin project discussion create",
            "project.note.create" => "Admin project note create",
            "project.file.create" => "Admin project file create",
            "lead.create" => "Admin leads create",
            "proposal.create" => "Admin proposals create",
            "type.create" => "Admin types create",
            "source.create" => "Admin sources create",
            "status.create" => "Admin statuses create",
            "leave.create_modal" => "Hrm leave create-modal",
            "leave.create" => "Hrm leave create",
            "assignLeave.create" => "Hrm leave assign create",
            "leaveRequest.create" => "Hrm leave request create",
            "designation.create_modal" => "Hrm designation create-modal",
            "designation.create" => "Hrm designation create",
            "department.create_modal" => "Hrm department create-modal",
            "department.create" => "Hrm department create",
            "role.create_modal" => "Hrm roles create-modal",
            "roles.create" => "Hrm roles create",
            "appointment.create" => "Hrm appointment create",
            "supportTicket.create" => "Hrm support ticket create",
            "performance.indicator.create" => "Admin performance indicator create",
            "performance.appraisal.create" => "Admin performance appraisal create",
            "performance.goal.create" => "Admin performance goal create",
            "performance.competence.type.create" => "Admin performance settings competence-type create",
            "performance.competence.create" => "Admin performance settings competence create",
            "performance.goal_type.create" => "Admin performance settings goal-type create",
            "company.create" => "Dashboard subscriptions create",
            "team.create" => "Dashboard teams create",
            "notice.create" => "Dashboard announcement notice create",
            "sms.create" => "Dashboard announcement sms create",
            "virtual_meeting.create" => "Dashboard virtual-meeting create",
            "award.create" => "Admin award create",
            "award_type.create" => "Admin award type create",
            "client.create" => "Admin client create",
            "hrm.accounts.create" => "Hrm accounts create",
            "hrm.transactions.create" => "Hrm transactions create",
            "hrm.deposits.create" => "Hrm deposit create",
            "hrm.expenses.create" => "Hrm expenses create",
            "hrm.deposit_category.create" => "Hrm account-settings create",
            "hrm.payment_method.create" => "Hrm payment-methods create",
        ];


        foreach ($routes as $key => $value) {
            SearchMenu::create([
                'route_name' => $key,
                'title' => $value,
            ]);
        }
    }
}
