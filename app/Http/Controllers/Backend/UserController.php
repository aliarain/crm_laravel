<?php

namespace App\Http\Controllers\Backend;

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
use App\Repositories\Leads\LeadRepository;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Models\Hrm\Designation\Designation;
use App\Services\Management\ProjectService;
use App\Helpers\CoreApp\Traits\AuthorInfoTrait;
use App\Http\Requests\User\UserSecurityRequest;
use App\Repositories\Hrm\Visit\VisitRepository;
use App\Repositories\Hrm\Notice\NoticeRepository;
use App\Repositories\Hrm\Payroll\SalaryRepository;
use App\Repositories\Hrm\Payroll\AdvanceRepository;
use App\Http\Requests\Hrm\User\ProfileUpdateRequest;
use App\Repositories\Support\SupportTicketRepository;
use App\Http\Requests\coreApp\User\UserProfileRequest;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Leave\LeaveRequestRepository;
use App\Repositories\Settings\CompanyConfigRepository;
use App\Repositories\Hrm\Employee\AppoinmentRepository;
use App\Repositories\Report\AttendanceReportRepository;
use App\Repositories\Hrm\Payroll\PayrollSetUpRepository;
use App\Repositories\Hrm\Attendance\AttendanceRepository;
use App\Repositories\Hrm\Department\DepartmentRepository;
use App\Repositories\Hrm\Designation\DesignationRepository;
use App\Repositories\Settings\ProfileUpdateSettingRepository;

class UserController extends Controller
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
    protected $companyConfigRepo;
    protected $leadRepository;


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
        PayrollSetUpRepository         $payrollSetupRepository,
        CompanyConfigRepository        $companyConfigRepo,
        AdvanceRepository              $advanceRepository,
        LeadRepository                 $leadRepository
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
        $this->companyConfigRepo = $companyConfigRepo;
        $this->leadRepository = $leadRepository;
    }

    public function profile(Request $request, $slug)
    {
        try {
            $user = $request->user();
            if (!myCompanyData($user->company_id)) {
                Toastr::warning('You Can\'t access!', 'Access Denied');
                return redirect()->back();
            }
            $data['title'] = _trans('common.Profile');
            $data['slug'] = $slug;
            $data['id'] = auth()->user()->id;
            $request['user_id'] = auth()->user()->id;
            $data['show'] = $this->profile->getProfile($request, $slug);
            return view('backend.user.staff.show', compact('data'));
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
    public function userInfo(Request $request, $user_id, $slug)
    {
        try {
            $user = $request->user();
            if (!myCompanyData($user->company_id)) {
                Toastr::warning('You Can\'t access!', 'Access Denied');
                return redirect()->back();
            }
            $data['title']      = plain_text($slug);
            $data['slug']       = $slug;
            $data['id']         = $user_id;
            $request['user_id'] = $user_id;
            $data['show']       = $this->profile->getProfile($request, $slug);
            switch ($slug) {
                case 'tasks':
                    $data['table']     = route('task.table');
                    $data['url_id']    = 'task_table_url';
                    $data['fields']    = $this->taskService->fields();
                    $data['class']     = 'task_table_class';
                    break;
                case 'award':
                    $data['title']     = _trans('award.Award List');
                    $data['table']     = route('award.table');
                    $data['url_id']    = 'award_table_url';
                    $data['fields']    = $this->awardService->fields();
                    $data['class']     = 'award_table_class';
                    break;
                case 'travel':
                    $data['title']     = _trans('travel.Travel List');
                    $data['table']     = route('user.profileDataTable', ['user_id' => $data['id'], 'type' => 'travel']);
                    $data['url_id']    = 'travel_table_url';
                    $data['fields']    = $this->travelService->fields();
                    $data['class']     = 'travel_table_class';
                    break;
                case 'support':
                    $data['title'] = _trans('support.Support ticket');
                    $data['url'] = route('supportTicket.dataTable');
                    break;

                default:
                    break;
            }

            return view('backend.user.' . $slug, compact('data'));
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
    public function staffInfo(Request $request, $slug)
    {
        try {
            $user = $request->user();
            if (!myCompanyData($user->company_id)) {
                Toastr::warning('You Can\'t access!', 'Access Denied');
                return redirect()->back();
            }
            $data['title']      = plain_text($slug);
            $data['slug']       = $slug;
            $data['id']         = auth()->user()->id;
            $request['user_id'] = auth()->user()->id;
            $data['show']       = $this->profile->getProfile($request, $slug);
            switch ($slug) {
                case 'tasks':
                    $data['table']     = route('task.table');
                    $data['url_id']    = 'task_table_url';
                    $data['fields']    = $this->taskService->fields();
                    $data['class']     = 'task_table_class';
                    break;
                case 'award':
                    $data['title']     = _trans('award.Award List');
                    $data['table']     = route('award.table');
                    $data['url_id']    = 'award_table_url';
                    $data['fields']    = $this->awardService->fields();
                    $data['class']     = 'award_table_class';
                    break;
                case 'travel':
                    $data['title']     = _trans('travel.Travel List');
                    $data['table']     = route('travel.table');
                    $data['url_id']    = 'travel_table_url';
                    $data['fields']    = $this->travelService->fields();
                    $data['class']     = 'travel_table_class';
                    break;

                default:
                    # code...
                    break;
            }

            return view('backend.user.staff.' . $slug, compact('data'));
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
    public function profileInfo(Request $request, $slug)
    {
        try {
            $user = $request->user();
            if (!myCompanyData($user->company_id)) {
                Toastr::warning('You Can\'t access!', 'Access Denied');
                return redirect()->back();
            }
            $data['title'] = _trans('common.Co-Worker Details');
            $data['slug'] = $slug;
            $data['id'] = auth()->user()->id;
            $request['user_id'] = auth()->user()->id;
            $data['show'] = $this->profile->getProfile($request, $slug);
            return view('backend.user.staff.show', compact('data'));
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    // user datatable
    function userDataTable(Request $request, $user_id, $slug)
    {
        switch ($slug) {
            case 'attendance':
                $table = $this->attendanceReportRepository->getAttendanceDataTable($request);
                break;

            case 'visit':
                $table =  $this->visitRepository->getUserVisitListForWeb($request);
                break;
            case 'phonebook':
                $table =  $this->user->departmentWiseUser($request);
                break;
            case 'appointment':
                $table =  $this->appointmentRepository->staffProfileDatatable($request, $request->id);
                break;
            case 'ticket':
                $table =  $this->supportTicketRepository->staffSupportDataTable($request);
                break;
            case 'advance':
                $table =  $this->advanceRepository->userDataTable($request, $user_id);
                break;
            case 'contract':
                $table =  $this->profile->getProfile($request, $slug);
                break;
            case 'notice':
                $table =  $this->noticeRepository->noticeDatatable($request);
                break;
            case 'salary':
                $table =   $this->salaryRepository->userDataTable($request, $user_id);
                break;
            case 'project':
                $table =   $this->projectService->userDataTable($request, $user_id);
                break;
            case 'tasks':
                $table =   $this->taskService->userDatatable($request, $user_id);
                break;
            case 'award':
                $table =   $this->awardService->userDatatable($request, $user_id);
                break;
            case 'travel':
                $table =   $this->travelService->userDatatable($request, $user_id);
                break;

            default:
                # code...
                break;
        }


        return $table;
    }
    // auth user datatable
    function authUserDataTable(Request $request, $slug)
    {
        switch ($slug) {
            case 'attendance':
                $table = $this->attendanceReportRepository->getAttendanceDataTable($request);
                break;

            case 'visit':
                $table =  $this->visitRepository->getUserVisitListForWeb($request);
                break;
            case 'phonebook':
                $table =  $this->user->departmentWiseUser($request);
                break;
            case 'appointment':
                $table =  $this->appointmentRepository->staffProfileDatatable($request, $request->id);
                break;
            case 'ticket':
                $table =  $this->supportTicketRepository->staffSupportDataTable($request);
                break;
            case 'advance':
                $table =  $this->advanceRepository->userDataTable($request, auth()->user()->id);
                break;
            case 'contract':
                $table =  $this->profile->getProfile($request, $slug);
                break;
            case 'notice':
                $table =  $this->noticeRepository->noticeDatatable($request);
                break;
            case 'salary':
                $table =   $this->salaryRepository->staffDataTable($request);
                break;
            case 'project':
                $table =   $this->projectService->datatable($request);
                break;
            case 'tasks':
                $table =   $this->taskService->datatable($request);
                break;

            default:
                # code...
                break;
        }


        return $table;
    }

    function security(Request $request)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        $this->validate($request, array(
            'email' => 'nullable|max:250',
            'old_password' => 'nullable|max:250',
            'password' => 'confirmed|min:8|different:old_password',

        ));
        DB::beginTransaction();
        try {
            $data = User::find($request->_id);

            if (!Hash::check($request['old_password'], $data->password)) {
                Toastr::error('The old password does not match our records.', 'Error');
                return redirect()->back();
            }
            $data->email = $request->email;
            $data->password = Hash::make($request->password);
            $data->save();
            DB::commit();
            Toastr::success(_trans('response.Password change successful'), 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function update(Request $request, User $user)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        try {
            $result = $this->user->update($request, $user->id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('user.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return back();
        }
    }

    public function updateProfile(UserProfileRequest $request)
    {
        return $this->user->updateProfile($request);
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $this->user->table($request);
            }
            $data['title'] = _trans('common.Employee List');
            $data['designations'] = $this->designation->getActiveAll();
            $data['class']  = 'users_table';
            $data['fields'] = $this->user->fields();
            $data['checkbox'] = true;


            return view('backend.user.index', compact('data'));
        } catch (\Exception $exception) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return back();
        }
    }

    public function create()
    {
        $data['title'] = _trans('common.Add Employee');
        $data['designations'] = $this->designation->getActiveAll();
        $data['departments'] = $this->department->getActiveAll();
        $data['shifts'] = $this->user->getActiveShift();
        $data['roles'] = $this->role->getAll();
        $data['permissions'] = Permission::get();
       
        return view('backend.user.employee.add_user', compact('data'));
       
    }

    public function store(UserStoreRequest $request)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        try {
            $result = $this->user->save($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('user.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return back();
        }
    }

    public function permissionEdit($id){

        try {
            $data['title'] = _trans('common.Permission Edit');
            $data['show'] = $this->user->getById($id);
            $data['permissions'] = Permission::get();
            return view('backend.user.employee.permission', compact('data'));
        } catch (\Exception $exception) {
            Toastr::error('Something went wrong.', 'Error');
        }

    }

    public function permissionUpdate(Request $request, $id)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        try {
            $result = $this->user->permission_update($request, $id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('user.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return back();
        }
    }


    public function delete(User $user)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        try {
            $this->user->delete($user);
            Toastr::success(_trans('common.User Deleted Successfully'), 'Success');
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return back();
        }
    }

    public function changeStatus(User $user, $status)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        try {
            $this->user->changeStatus($user, $status);
            Toastr::success('User status change Successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return back();
        }
    }

    public function restore($id){

        try {
            $this->user->restore($id);
            Toastr::success(_trans('message.User has been restored'), 'Success');
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return back();
        }

    }

    public function show($id)
    {
        try {
            return $this->user->getById($id);
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function profileView(Request $request, User $user, $slug)
    {
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
            $data['designations'] = $this->designation->getActiveAll();
            $data['departments'] = $this->department->getActiveAll();
            $data['shifts'] = $this->user->getShift();
        } elseif ($slug == 'attendance') {
            $data['class']  = 'attendance_table';
            $data['fields'] = $this->attendance_repo->fields();
            $data['table']     = route('attendance.auth_user_profile_table');
            $data['url_id']    = 'attendance_table_url';
        } elseif ($slug == 'notice') {
            $data['fields'] = $this->noticeRepository->fields();
            $data['url_id']        = 'notice_table_url';
            $data['class']         = 'table_class';
            $data['table']     = route('notice.auth_user_profile_table');
        } elseif ($slug == 'leave_request') {
            $data['class']  = 'leave_request_table';
            $data['fields'] = $this->leaveRequest->fields();
            $data['table']     = route('leave_request.auth_user_profile_table');
            $data['url_id']        = 'leave_request_table_url';
        } elseif ($slug == 'visit') {
            $data['fields'] = $this->visitRepository->fields();
            $data['table']    = route('visit.auth_user_profile_table');
            $data['url_id']    = 'visit_table_url';
            $data['class']     = 'table_class';
        } elseif ($slug == 'phonebook') {
            $data['fields']    = $this->user->phonebookFields();
            $data['table']     = route('user.phonebookTable');
            $data['url_id']    = 'phonebook_table_url';
            $data['class']     = 'table_class';
        } elseif ($slug == 'appointment') {
            $data['fields']    = $this->appointmentRepository->fields();
            $data['table']    = route('appointment.auth_user_profile_table');
            $data['url_id']    = 'appointment_table_url';
            $data['class']     = 'table_class';
        } elseif ($slug == 'ticket') {
            $data['fields']        = $this->supportTicketRepository->fields();
            $data['table']         = route('supportTicket.user_table');
            $data['url_id']        = 'support_table_url';
            $data['class']         = 'table_class';
        } elseif ($slug == 'advance') {
            $data['class']      = 'salary_advance_table';
            $data['fields']     = $this->advanceRepository->fields();
            $data['table']      = route('advance.auth_user_profile_table');
            $data['url_id']     = 'salary_advance_table_url';
        } elseif ($slug == 'commission') {
            $data['fields'] = $this->payrollSetupRepository->setCommissionFields();
            $data['table']     = route('commission.auth_user_profile_table',  $data['id']);
            $data['url_id']    = 'payroll_item_set_up_table_url';
            $data['class']     = 'table_class';
        } elseif ($slug == 'salary') {
            $data['fields'] = $this->salaryRepository->fields();
            $data['table']     = route('salary.auth_user_profile_table',  $data['id']);
            $data['url_id']    = 'salary_table_url';
            $data['class']     = 'salary_table';
        } elseif ($slug == 'project') {
            $data['fields']    = $this->projectService->fields();
            $data['table']     = route('project.auth_user_profile_table');
            $data['url_id']    = 'project_table_url';
            $data['class']     = 'table_class';
        } elseif ($slug == 'task') {
            $data['fields']    = $this->taskService->fields();
            $data['table'] = route('task.auth_user_profile_table');
            $data['url_id']        = 'task_table_url';
            $data['class']         = 'task_table';
        } elseif ($slug == 'award') {
            $data['table'] = route('award.auth_user_profile_table');
            $data['fields']    = $this->awardService->fields();
            $data['title']     = _trans('award.Award List');
            $data['url_id']    = 'award_table_url';
            $data['class']     = 'award_table_class';
        } elseif ($slug == 'travel') {
            $data['table'] = route('travel.auth_user_profile_table');
            $data['url_id']    = 'travel_table_url';
            $data['fields']    = $this->travelService->fields();
            $data['class']     = 'travel_table_class';
        }



        $data['name'] = @$user->name;
        $data['department'] = @$user->department->title;
        return view('backend.employee.details', compact('data'));
        if (auth()->user()->role->name == "Staff") {
            if ($user->id == auth()->id()) {
                return view('backend.user.show', compact('data'));
            } else {
                return back();
            }
        } else {
            return view('backend.user.show', compact('data'));
        }
    }

    public function profileEditView(Request $request, User $user, $slug)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        $data['title'] = _trans('common.Edit Co-Worker');
        $data['slug'] = $slug;
        $data['id'] = $user->id;
        $data['departments'] = $this->profileSetting->getAllDepartment();
        $data['designations'] = $this->profileSetting->getAllDesignation();
        $data['managers'] = $this->user->getActiveAll();
        $request['user_id'] = $user->id;
        $data['show'] = $this->profile->getProfile($request, $slug);
        return view('backend.user.edit', compact('data'));
    }
    public function staffProfileEditView(Request $request, $slug)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        $user = auth()->user();
        $data['title'] = _trans('common.Edit Profile');
        $data['slug'] = $slug;
        $data['id'] = $user->id;
        $data['departments'] = $this->profileSetting->getAllDepartment();
        $data['designations'] = $this->profileSetting->getAllDesignation();
        $data['managers'] = $this->user->getActiveAll();
        $request['user_id'] = $user->id;
        $data['show'] = $this->profile->getProfile($request, $slug);
        return view('backend.user.staff.edit', compact('data'));
    }



    public function profileUpdate(ProfileUpdateRequest $request, User $user, $slug)
    {
        
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        try {
            
            $update = $this->profile->updateProfile($request, $slug);
            if ($update) {
                Toastr::success(_trans('response.Profile updated successfully'), 'Success');
                return back();
            } else {
                Toastr::error(_trans('response.Something went wrong.'), 'Error');
                return back();
            }
        } catch (\Exception $exception) {
            dd($exception);
            Toastr::error('Something went wrong.', 'Error');
            return back();
        }
    }

    public function edit(User $user)
    {
        try {
            $data['title'] = _trans('common.Edit information');
            $data['show'] = $user;
            $data['designations'] = $this->designation->getActiveAll();
            $data['departments'] = $this->department->getActiveAll();
            $data['shifts'] = $this->user->getActiveShift();
            $data['roles'] = $this->role->getAll();
            $data['timezones'] = $this->companyConfigRepo->time_zone();
            $data['permissions'] = Permission::get();
            return view('backend.user.employee.edit_view', compact('data'));
        } catch (\Exception $exception) {
            Toastr::error('Something went wrong.', 'Error');
        }
    }

    public function support(Request $request, $id)
    {
        try {
            $data['id'] = $id;
            $data['title'] = _trans('common.Support Ticket List');
            $data['permissions'] = Permission::get();
            return view('backend.user.support', compact('data'));
        } catch (\Exception $exception) {
            Toastr::error('Something went wrong.', 'Error');
        }
    }


    public function supportTicketsDataTable(Request $request)
    {
        return $this->supportTicketRepository->dataTable($request);
    }

    public function attendance(Request $request, $id)
    {
        try {
            $user = $this->user->getById($id);
            if ($user) {
                if (!myCompanyData($user->company_id)) {
                    Toastr::warning('You Can\'t access!', 'Access Denied');
                    return redirect()->back();
                }
                if (auth()->user()->role->slug == 'staff' && auth()->id() != $id) {
                    return abort(403);
                }
                $data['id'] = $id;
                $data['title'] = _trans('common.Attendance List');
                $data['users'] = $this->user->getActiveAll();
                $data['departments'] = $this->department->getActiveAll();
                $data['permissions'] = Permission::get();
                $data['url'] = route('user.attendanceTable', $id);
                return view('backend.user.attendance', compact('data'));
            } else {
                Toastr::error('User Not Found.', 'Error');
                return back();
            }
        } catch (\Exception $exception) {
            Toastr::error('Something went wrong.', 'Error');
            return back();
        }
    }

    public function leaveRequest(Request $request, $id)
    {
        try {
            $user = $this->user->getById($id);
            if ($user) {
                if (!myCompanyData($user->company_id)) {
                    Toastr::warning('You Can\'t access!', 'Access Denied');
                    return redirect()->back();
                }
                if (auth()->user()->role->slug == 'staff' && auth()->id() != $id) {
                    return abort(403);
                }
                $data['id'] = $id;
                $data['title'] = _trans('common.Leave Request List');
                return view('backend.user.leave_request', compact('data'));
            } else {
                Toastr::error('User Not Found.', 'Error');
                return back();
            }
        } catch (\Exception $exception) {
            Toastr::error('Something went wrong.', 'Error');
        }
    }

    public function leaveRequestApproved(Request $request, $id)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        try {
            $user = $this->user->getById($id);
            if ($user) {
                if (!myCompanyData($user->company_id)) {
                    Toastr::warning('You Can\'t access!', 'Access Denied');
                    return redirect()->back();
                }
                if (auth()->user()->role->slug == 'staff' && auth()->id() != $id) {
                    return abort(403);
                }
                $data['id'] = $id;
                $data['title'] = _trans('common.Approved Leave Request List');
                return view('backend.user.approved_leave_request', compact('data'));
            } else {
                Toastr::error('User Not Found.', 'Error');
                return back();
            }
        } catch (\Exception $exception) {
            Toastr::error('Something went wrong.', 'Error');
        }
    }

    public function notice(Request $request, $id)
    {
        try {
            $user = $this->user->getById($id);
            if ($user) {
                if (!myCompanyData($user->company_id)) {
                    Toastr::warning('You Can\'t access!', 'Access Denied');
                    return redirect()->back();
                }
                if (auth()->user()->role->slug == 'staff' && auth()->id() != $id) {
                    return abort(403);
                }
                $data['id'] = $id;
                $data['user'] = $this->user->getById($id);
                $data['title'] = _trans('common.Notice List');
                return view('backend.user.notice', compact('data'));
            } else {
                Toastr::error('User Not Found.', 'Error');
                return back();
            }
        } catch (\Exception $exception) {
            Toastr::error('Something went wrong.', 'Error');
        }
    }

    public function clearNotice(Request $request)
    {
        return $this->attendanceReportRepository->attendanceProfileDatatable($request);
        return $this->noticeRepository->clearNotice();
    }

    public function attendanceListDataTable(Request $request, $id)
    {
        try {

            $request['user_id'] = $id;
            return $this->attendanceReportRepository->attendanceDatatable($request);
        } catch (\Throwable $th) {
        }
    }

    function noticeDatatable(Request $request)
    {
        try {
            return $this->noticeRepository->noticeDatatable($request);
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    function data_table(Request $request)
    {
        try {
            return $this->user->data_table($request);
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }
    function makeHR(Request $request, $user_id)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        try {
            $result = $this->user->makeHR($user_id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->back();
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }
    public function defaultPassword(Request $request, $user_id)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        try {
            $result = $this->user->defaultPassword($user_id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->back();
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function getUsers(Request $request)
    {
        
        if (!is_Admin()) {
            $request['params'] =  ['id' => auth()->id()];
        }
        return $this->user->getUserByKeywords($request);
    }
    public function getActiveUsers(Request $request)
    {
  
        $request['params'] =  ['status_id' => 1];
        return $this->user->getUserByKeywords($request);
    }

    public function phonebook($id)
    {
        try {
            $user = $this->user->getById($id);
            if ($user) {
                if (!myCompanyData($user->company_id)) {
                    Toastr::warning('You Can\'t access!', 'Access Denied');
                    return redirect()->back();
                }
                if (auth()->user()->role->slug == 'staff' && auth()->id() != $id) {
                    return abort(403);
                }
                $data['id'] = $id;
                $data['title'] = _trans('common.Phonebook');
                $data['user'] = $this->user->getById($id);
                return view('backend.user.phonebook', compact('data'));
            } else {
                Toastr::error('User Not Found.', 'Error');
                return back();
            }
        } catch (\Exception $exception) {
            Toastr::error('Something went wrong.', 'Error');
        }
    }

    public function phonebookDatatable(Request $request)
    {
        try {
            return $this->user->departmentWiseUser($request);
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function appointment($id)
    {
        try {
            $user = $this->user->getById($id);
            if ($user) {
                if (!myCompanyData($user->company_id)) {
                    Toastr::warning('You Can\'t access!', 'Access Denied');
                    return redirect()->back();
                }
                if (auth()->user()->role->slug == 'staff' && auth()->id() != $id) {
                    return abort(403);
                }

                $data['id'] = $id;
                $data['title'] = _trans('common.Appointment List');
                $data['user'] = $this->user->getById($id);
                return view('backend.user.appointment', compact('data'));
            } else {
                Toastr::error('User Not Found.', 'Error');
                return back();
            }
        } catch (\Exception $exception) {
            Toastr::error('Something went wrong.', 'Error');
        }
    }

    // status change
    public function statusUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        return $this->user->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->user->destroyAll($request);
    }

    public function newPhonebookDatatable(Request $request)
    {
        if ($request->ajax()) {
            return $this->user->phoneBookTable($request);
        }
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
        $data['email'] = $user->email;
        $request['user_id'] = $user->id;
        $data['show'] = $this->profile->getProfile($request, $slug);

        if ($slug == 'personal') {
            $data['designations'] = $this->designation->getActiveAll();
            $data['departments'] = $this->department->getActiveAll();
            $data['shifts'] = $this->user->getShift();
        } elseif ($slug == 'attendance') {
            $data['class']  = 'attendance_table';
            $data['fields'] = $this->attendance_repo->fields();
            $data['table']     = route('attendance.auth_user_profile_table');
            $data['url_id']    = 'attendance_table_url';
        } elseif ($slug == 'notice') {
            $data['fields'] = $this->noticeRepository->fields();
            $data['url_id']        = 'notice_table_url';
            $data['class']         = 'table_class';
            $data['table']     = route('notice.auth_user_profile_table');
        } elseif ($slug == 'leave_request') {
            $data['class']  = 'leave_request_table';
            $data['fields'] = $this->leaveRequest->fields();
            $data['table']     = route('leave_request.auth_user_profile_table');
            $data['url_id']        = 'leave_request_table_url';
        } elseif ($slug == 'visit') {
            $data['fields'] = $this->visitRepository->fields();
            $data['table']    = route('visit.auth_user_profile_table');
            $data['url_id']    = 'visit_table_url';
            $data['class']     = 'table_class';
        } elseif ($slug == 'phonebook') {
            $data['fields']    = $this->user->phonebookFields();
            $data['table']     = route('user.phonebookTable');
            $data['url_id']    = 'phonebook_table_url';
            $data['class']     = 'table_class';
        } elseif ($slug == 'appointment') {
            $data['fields']    = $this->appointmentRepository->fields();
            $data['table']    = route('appointment.auth_user_profile_table');
            $data['url_id']    = 'appointment_table_url';
            $data['class']     = 'table_class';
        } elseif ($slug == 'ticket') {
            $data['fields']        = $this->supportTicketRepository->fields();
            $data['table']         = route('supportTicket.user_table');
            $data['url_id']        = 'support_table_url';
            $data['class']         = 'table_class';
        } elseif ($slug == 'advance') {
            $data['class']      = 'salary_advance_table';
            $data['fields']     = $this->advanceRepository->fields();
            $data['table']      = route('advance.auth_user_profile_table');
            $data['url_id']     = 'salary_advance_table_url';
        } elseif ($slug == 'commission') {
            $data['fields'] = $this->payrollSetupRepository->setCommissionFields();
            $data['table']     = route('commission.auth_user_profile_table',  $data['id']);
            $data['url_id']    = 'payroll_item_set_up_table_url';
            $data['class']     = 'table_class';
        } elseif ($slug == 'salary') {
            $data['fields'] = $this->salaryRepository->fields();
            $data['table']     = route('salary.auth_user_profile_table',  $data['id']);
            $data['url_id']    = 'salary_table_url';
            $data['class']     = 'salary_table';
        } elseif ($slug == 'project') {
            $data['fields']    = $this->projectService->fields();
            $data['table']     = route('project.auth_user_profile_table');
            $data['url_id']    = 'project_table_url';
            $data['class']     = 'table_class';
        } elseif ($slug == 'task') {
            $data['fields']    = $this->taskService->fields();
            $data['table'] = route('task.auth_user_profile_table');
            $data['url_id']        = 'task_table_url';
            $data['class']         = 'task_table';
        } elseif ($slug == 'award') {
            $data['table'] = route('award.auth_user_profile_table');
            $data['fields']    = $this->awardService->fields();
            $data['title']     = _trans('award.Award List');
            $data['url_id']    = 'award_table_url';
            $data['class']     = 'award_table_class';
        } elseif ($slug == 'travel') {
            $data['table'] = route('travel.auth_user_profile_table');
            $data['url_id']    = 'travel_table_url';
            $data['fields']    = $this->travelService->fields();
            $data['class']     = 'travel_table_class';
        }
        $data['name'] = @$user->name;
        $data['department'] = @$user->department->title;

        $data['totalTask'] = User::where('id', $data['id'])->first()->totalTask();
        $data['totalProject'] =  User::where('id', $data['id'])->first()->totalProject();
        $data['totalLead'] = $this->leadRepository->totalLead($data['id']);
        return view('backend.profile.details', compact('data'));
    }

    function webSecurity(UserSecurityRequest $request)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        DB::beginTransaction();
        try {
            $data = User::find($request->user_id);
            if (!Hash::check($request['old_password'], $data->password)) {
                Toastr::error('The old password does not match our records.', 'Error');
                return redirect()->back();
            }
            $data->email = $request->email;
            $data->password = Hash::make($request->password);
            $data->save();
            DB::commit();
            Toastr::success(_trans('response.Password change successful'), 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
}
