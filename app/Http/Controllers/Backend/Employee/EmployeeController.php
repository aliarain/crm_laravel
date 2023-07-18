<?php

namespace App\Http\Controllers\Backend\Employee;

use Svg\Tag\Rect;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Task\TaskService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Services\Award\AwardService;
use Brian2694\Toastr\Facades\Toastr;
use function GuzzleHttp\Promise\all;
use Illuminate\Support\Facades\Hash;
use App\Models\Permission\Permission;
use App\Services\Travel\TravelService;
use App\Http\Requests\UserStoreRequest;
use App\Repositories\ProfileRepository;
use App\Models\Hrm\Department\Department;
use App\Repositories\Admin\RoleRepository;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Models\Hrm\Designation\Designation;
use App\Services\Management\ProjectService;
use App\Helpers\CoreApp\Traits\AuthorInfoTrait;
use App\Repositories\Hrm\Visit\VisitRepository;
use App\Repositories\Hrm\Notice\NoticeRepository;
use App\Repositories\Hrm\Payroll\SalaryRepository;
use App\Repositories\Hrm\Payroll\AdvanceRepository;
use App\Http\Requests\Hrm\User\ProfileUpdateRequest;
use App\Repositories\Support\SupportTicketRepository;
use App\Http\Requests\coreApp\User\UserProfileRequest;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Leave\LeaveRequestRepository;
use App\Repositories\Hrm\Employee\AppoinmentRepository;
use App\Repositories\Report\AttendanceReportRepository;
use App\Repositories\Hrm\Payroll\PayrollSetUpRepository;
use App\Repositories\Hrm\Attendance\AttendanceRepository;
use App\Repositories\Hrm\Department\DepartmentRepository;
use App\Repositories\Hrm\Designation\DesignationRepository;
use App\Repositories\Settings\ProfileUpdateSettingRepository;

class EmployeeController extends Controller
{
    use FileHandler, AuthorInfoTrait, RelationshipTrait;

    protected $user;
    protected $role;
    protected $profile;
    protected $profileSetting;
    protected $designation;
    protected $department;
    protected $noticeRepository;

    protected $supportTicketRepository;
    protected $visitRepository;
    protected $appointmentRepository;
    protected $salaryRepository;
    protected $projectService;
    protected $taskService;
    protected $awardService;
    protected $travelService;
    protected $advanceRepository;
    protected $attendance_repo;
    protected $leaveRequest;
    protected $payrollSetupRepository;

    public function __construct(
        UserRepository                 $user,
        RoleRepository                 $roleRepository,
        ProfileRepository              $profileRepository,
        DesignationRepository          $designation,
        DepartmentRepository           $department,
        ProfileUpdateSettingRepository $profileUpdateSettingRepository,
        SupportTicketRepository        $supportTicketRepository,
        AttendanceReportRepository     $attendanceReportRepository,
        NoticeRepository               $noticeRepository,
        VisitRepository                $visitRepository,
        AppoinmentRepository           $appointmentRepository,
        SalaryRepository               $salaryRepository,
        ProjectService                 $projectService,
        TaskService                    $taskService,
        AwardService                   $awardService,
        TravelService                  $travelService,
        AttendanceRepository           $attendance_repo,
        LeaveRequestRepository         $leaveRequestRepository,
        PayrollSetUpRepository           $payrollSetupRepository,
        AdvanceRepository              $advanceRepository
    ) {
        $this->user = $user;
        $this->role = $roleRepository;
        $this->profile = $profileRepository;
        $this->profileSetting = $profileUpdateSettingRepository;
        $this->designation = $designation;
        $this->department = $department;
        $this->supportTicketRepository = $supportTicketRepository;
        $this->attendanceReportRepository = $attendanceReportRepository;
        $this->noticeRepository = $noticeRepository;
        $this->visitRepository = $visitRepository;
        $this->appointmentRepository = $appointmentRepository;
        $this->salaryRepository = $salaryRepository;
        $this->projectService = $projectService;
        $this->taskService = $taskService;
        $this->awardService = $awardService;
        $this->travelService = $travelService;
        $this->advanceRepository = $advanceRepository;
        $this->attendance_repo = $attendance_repo;
        $this->leaveRequest = $leaveRequestRepository;
        $this->payrollSetupRepository = $payrollSetupRepository;
    }


    public function authProfile(Request $request, $slug)
    {
        $user = auth()->user();
        if (!myCompanyData($user->company_id)) {
            Toastr::warning('You Can\'t access!', 'Access Denied');
            return redirect()->back();
        }


        $data['title'] = _trans('common.Employee Details');
        $data['slug'] = $slug;
        $data['id'] = $user->id;
        $request['user_id'] = $user->id;
        $data['show'] = $this->profile->getProfile($request, $slug);

        if ($slug == 'personal') {
            $data['designations'] = $this->designation->getAll();
            $data['departments'] = $this->department->getAll();
            $data['shifts'] = $this->user->getShift();
        }elseif ($slug == 'attendance') {
            $data['class']  = 'attendance_table';
            $data['fields'] = $this->attendance_repo->fields();
            $data['table']     = route('attendance.em_profile');
            $data['url_id']    = 'attendance_table_url';
        }elseif ($slug == 'notice') {
            $data['fields'] = $this->noticeRepository->fields();
            $data['url_id']        = 'notice_table_url';
            $data['class']         = 'table_class';
            $data['table']     = route('notice.em_profile');
        }elseif($slug == 'leave_request') {
            $data['class']  = 'leave_request_table';
            $data['fields'] = $this->leaveRequest->fields();
            $data['table']     = route('leave_request.em_profile');
            $data['url_id']        = 'leave_request_table_url';
        }elseif($slug == 'visit') {
            $data['fields'] = $this->visitRepository->fields();
            $data['table']    = route('visit.em_profile');
            $data['url_id']    = 'visit_table_url';
            $data['class']     = 'table_class';
        }elseif($slug == 'phonebook') {
            $data['fields']    = $this->user->phonebookFields();
            $data['table']     = route('phonebookTable.em_profile');
            $data['url_id']    = 'phonebook_table_url';
            $data['class']     = 'table_class';
        }elseif($slug == 'appointment') {
            $data['fields']    = $this->appointmentRepository->fields();
            $data['table']    = route('appointment.em_profile');
            $data['url_id']    = 'appointment_table_url';
            $data['class']     = 'table_class';
        }elseif($slug == 'ticket') {
            $data['fields']        = $this->supportTicketRepository->fields();
            $data['table']         = route('supportTicket.em_profile');
            $data['url_id']        = 'support_table_url';
            $data['class']         = 'table_class';
        }elseif($slug == 'advance') {
            $data['class']      = 'salary_advance_table';
            $data['fields']     = $this->advanceRepository->fields();
            $data['table']      = route('advance.em_profile');
            $data['url_id']     = 'salary_advance_table_url';
        }elseif($slug == 'commission') {
            $data['fields'] = $this->payrollSetupRepository->setCommissionFields();
            $data['table']     = route('commission.em_profile');
            $data['url_id']    = 'payroll_item_set_up_table_url';
            $data['class']     = 'table_class';
        }elseif($slug == 'salary') {
            $data['fields'] = $this->salaryRepository->fields();
            $data['table']     = route('salary.em_profile');
            $data['url_id']    = 'salary_table_url';
            $data['class']     = 'salary_table';
        }elseif($slug == 'project') {
            $data['fields']    = $this->projectService->fields();
            $data['table']     = route('project.em_profile');
            $data['url_id']    = 'project_table_url';
            $data['class']     = 'table_class';
        }elseif($slug == 'task') {
            $data['fields']    = $this->taskService->fields();
            $data['table'] = route('task.em_profile');
            $data['url_id']        = 'task_table_url';
            $data['class']         = 'task_table';
        }elseif($slug == 'award') {
            $data['table'] = route('award.em_profile');
            $data['fields']    = $this->awardService->fields();
            $data['title']     = _trans('award.Award List');
            $data['url_id']    = 'award_table_url';
            $data['class']     = 'award_table_class';
        }elseif($slug == 'travel') {
            $data['table'] = route('travel.em_profile');
            $data['url_id']    = 'travel_table_url';
            $data['fields']    = $this->travelService->fields();
            $data['class']     = 'travel_table_class';
        }
        $data['name'] = @$user->name;
        $data['department'] = @$user->department->title;
        return view('backend.profile.details', compact('data'));
    }


    public function fileView($image){
        try {
            $data['image'] = uploaded_asset($image);
            return view('backend.modal.image_view', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }

    }


}
