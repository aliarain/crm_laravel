<?php

namespace App\Repositories\Api\V1;

use DateTime;
use Validator;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Skill;
use App\Models\Role\Role;
use App\Models\StockBrand;
use Illuminate\Support\Str;
use App\Models\Notification;
use App\Models\StockProduct;
use App\Models\StockCategory;
use App\Models\Finance\Deposit;
use App\Models\Management\Client;
use App\Models\Management\Project;
use Illuminate\Support\Facades\DB;
use App\Models\Finance\Transaction;
use App\Models\TaskManagement\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\coreApp\Status\Status;
use App\Models\Management\ProjectFile;
use App\Models\TaskManagement\TaskFile;
use Illuminate\Support\Facades\Request;
use App\Models\Hrm\AppSetting\AppScreen;
use App\Models\Management\ProjectMembar;
use App\Models\Hrm\Department\Department;
use App\Models\Hrm\Support\SupportTicket;
use App\Models\Management\DiscussionLike;
use App\Models\TaskManagement\TaskMember;
use App\Repositories\DashboardRepository;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Models\Hrm\Designation\Designation;
use App\Repositories\DutyScheduleRepository;
use App\Models\coreApp\Setting\CompanyConfig;
use App\Models\TaskManagement\TaskDiscussion;
use App\Http\Resources\Hrm\UserListCollection;
use App\Models\Expenses\IncomeExpenseCategory;
use App\Repositories\Settings\ApiSetupRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Content\AllContentRepository;
use App\Repositories\Settings\CompanyConfigRepository;

class CrmAppRepository
{
    use RelationshipTrait, ApiReturnFormatTrait, FileHandler;

    protected $companyConfig;
    protected $appScreen;
    protected $dashboardRepository;
    protected $dutyScheduleRepository;
    protected $allContents;
    protected $thirdPartyApiRepository;
    protected $config_repo;

    public function __construct(
        CompanyConfig $companyConfig,
        AppScreen $appScreen,
        DashboardRepository $dashboardRepository,
        CompanyConfigRepository $companyConfigRepo,
        DutyScheduleRepository $dutyScheduleRepository,
        AllContentRepository $allContents,
        ApiSetupRepository $thirdPartyApiRepository
    ) {
        $this->companyConfig = $companyConfig;
        $this->appScreen = $appScreen;
        $this->dashboardRepository = $dashboardRepository;
        $this->config_repo = $companyConfigRepo;
        $this->dutyScheduleRepository = $dutyScheduleRepository;
        $this->allContents = $allContents;
        $this->thirdPartyApiRepository = $thirdPartyApiRepository;
    }
    public function GetAppDashboardScreen()
    {
        $menus = $this->appScreen->query()
            ->where('status_id', 1)
            ->orderBy('position', 'ASC')
            ->select('name', 'slug', 'position', 'icon', 'lavel')
            ->get();
        foreach ($menus as $menu) {
            $menu->image_type = pathinfo($menu->icon, PATHINFO_EXTENSION);
            $menu->icon = static_asset($menu->icon);
            $menu->position = intval($menu->position);
        }
        $collection = [
            'data' => $menus,
        ];
        return $this->responseWithSuccess('App Dashboard Screen Menus', $collection, 200);
    }
    public function GetAppHomeScreenUpdated()
    {
        $projects = Project::with('members')
            ->whereHas('members', function ($q) {
                $q->where('user_id', auth()->user()->id);
            })
            ->orderBy('id', 'DESC')
            ->take(5)
            ->get();

        $tasks = TaskMember::with('task')
            ->where('user_id', auth()->user()->id)
            ->whereHas('task', function ($q) {
                $q->where('status_id', 26);
            })
            ->orderBy('id', 'DESC')
            ->take(5)
            ->get();
        $project_summary = [
            'slug' => 'project-summary',
            'label' => 'Pro',
            'data' => $this->ProjectListCollection($projects),
        ];

        $staticstics = [
            'slug' => 'staticstics',
            'label' => 'Pro',
            'data' => [
                'months' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                'complete_projects' => [
                    12, 14, 15, 16, 17, 18, 19, 20, 21, 19, 20, 21,
                ],
                'incomplete_projects' => [5, 7, 9, 16, 21, 8, 9, 20, 1, 9, 12, 5],
            ],
        ];

        $tasks = [
            'slug' => 'tasks',
            'label' => 'Pro',
            'data' => $this->TasksCollection($tasks),
        ];

        $notices = [
            'slug' => _trans('common.Notification'),
            'label' => 'Pro',
            'data' => auth()->user()->unreadNotifications->take(5)->map(function ($notification) {
                return [
                    'title' => $notification->data['title'],
                    'message' => @$notification->data['body'],
                    'created_at' => Carbon::parse($notification->created_at)->diffForHumans(),
                ];
            }),
        ];
        $support_list = User::with('supports')->where('id', auth()->user()->id)->first();
        $supports = [
            'slug' => _trans('common.Support'),
            'label' => 'Pro',
            'data' => $support_list->supports->map(function ($support) {
                return [
                    'priority' => $support->priority->name,
                    'priority_color' => '0xff' . $support->priority->color_code,
                    'type' => $support->type->name,
                    'subject' => $support->subject,
                    'description' => Str::limit($support->description, 100),
                    'code' => $support->code,
                ];
            }),
        ];

        $calendar = [
            'slug' => 'notices',
            'label' => 'Pro',
            'data' => [
                'current_month' => date('M Y'),
                'selected_date' => date('d'),
            ],
        ];

        $projects = Project::take(5)->get();
        $collection = [
            'project_summary' => $project_summary,
            'staticstics' => $staticstics,
            'tasks' => $tasks,
            'projects' => $this->ProjectListCollection($projects),
            'notices' => $notices,
            'supports' => $supports,
            'calendar' => $calendar,
        ];
        // $collection = [ $collection];
        return $this->responseWithSuccess('App Home Screen Menus', $collection, 200);
    }

    // {{url}}app/home-screen
    public function GetAppHomeScreen($request)
    {
        $projects = Project::with('members')
            ->whereHas('members', function ($q) {
                $q->where('user_id', auth()->user()->id);
            })
            ->whereYear('end_date', '=', date('Y'))
            ->whereMonth('end_date', '=', date('m'))
            ->select('id', 'name', 'start_date', 'end_date', 'status_id')
            ->get();

        $project_summary = [
            'slug' => 'project-summary',
            'label' => 'Pro',
            'data' => [
                [
                    'name' => _trans('common.Complete Project'),
                    'slug' => 'complete-project',
                    'position' => 1,
                    'icon' => 'fa-briefcase',
                    'number' => $projects->where('status_id', 27)->count(),
                    'text' => _trans('common.Complete Project in this month'),
                    'end_point' => 'app/projects/list?status_id=27',
                    'button_text' => _trans('common.See More'),
                    'color' => 'C41C63',
                ],
                [
                    'name' => _trans('common.Incomplete Project'),
                    'slug' => 'running-project',
                    'position' => 1,
                    'icon' => 'fa-briefcase',
                    'number' => $projects->where('status_id', 26)->count(),
                    'text' => 'End date is this month',
                    'end_point' => 'app/projects/list?status_id=26',
                    'button_text' => _trans('common.See More'),
                    'color' => '6ECDC4',
                ],

            ],
        ];

     

        if ($request->statistic_duration == 'weekly') {
            $start_date = date('Y-m-d', strtotime(config('app.week_start_day') . " -1 week"));
            $end_date = date('Y-m-d', strtotime(config('app.week_start_day') . " -1 week +6 days"));

            $begin = new DateTime($start_date);
            $end = new DateTime($end_date);
            $end = $end->modify('+1 day');
        } else {
            $start_date = date('Y-m-d', strtotime('first day of this month'));
            $end_date = date("Y-m-t", strtotime($start_date));

            $begin = new DateTime($start_date);
            $end = new DateTime($end_date);
            $end = $end->modify('+1 day');

        }
        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval, $end);
        $my_tasks = TaskMember::join('tasks', 'tasks.id', 'task_members.task_id')
            ->where('user_id', auth()->user()->id)
            ->whereBetween('tasks.start_date', [$start_date, $end_date])
            ->select('tasks.id', 'tasks.status_id', 'tasks.end_date')
            ->get();
        $complete_tasks = [];
        foreach ($daterange as $key => $value) {
            $complete_tasks[] = [
                // 'date'=>$value->format('d D'),
                // 'complete' => $my_tasks->where('status_id', 27)->where('end_date', $value->format('Y-m-d'))->count(),
                'date' => intval($value->format('d')),
                'complete' => rand(1, 10),
            ];
        }
        $incomplete_tasks = [];
        foreach ($daterange as $key => $value) {
            $incomplete_tasks[] = [
                // 'date'=>$value->format('d D'),
                // 'incomplete' => $my_tasks->where('status_id', 26)->where('end_date', $value->format('Y-m-d'))->count(),
                'date' => intval($value->format('d')),
                'incomplete' => rand(1, 10),
            ];
        }
        $staticstics = [
            'slug' => 'staticstics',
            'label' => 'Pro',
            'data' => [
                'complete_tasks' => $complete_tasks,
                'incomplete_tasks' => $incomplete_tasks,
            ],
        ];

        $task_lists = TaskMember::join('tasks', 'tasks.id', 'task_members.task_id')->with('task', 'task.members')->where('user_id', auth()->user()->id)
            ->when(isset(request()->priority), function ($query) {
                return $query->where('tasks.priority', request()->priority);
            })
            ->when(isset(request()->keyword), function ($query) {
                return $query->where('name', 'LIKE', '%' . request()->keyword . '%');
            })->where('tasks.status_id', 26)
            ->paginate(10);

        $task_list_collection = $this->TaskListCollectionWithPagination($task_lists);
        $tasks = [
            'slug' => 'tasks',
            'label' => 'Pro',
            'title' => _trans('common.Tasks In Progress'),
            'end_point' => 'app/tasks/list?status=26',
            'data' => $task_list_collection['tasks'],
        ];

        $notifications = auth()->user()->notifications;

        $notification_collection = $notifications->map(function ($notification) {
            $sender_info = Notification::sender($notification->data['sender_id']);
            return [
                'sender' => @$sender_info->name,
                'title' => $notification->data['title'],
                'text' => $notification->data['body'],
                'avatar' => @uploaded_asset($sender_info->avatar_id),
                'date' => $notification->created_at->diffForHumans(),
                'slag' => $notification->data['actionURL']['app'],
                'read_at' => $notification->read_at,
                'is_read' => $notification->read_at ? true : false,
                'color' => '6ECDC4',
                'icon' => 'f121',
            ];
        });
        $notices = [
            'slug' => 'notices',
            'label' => 'Pro',
            'title' => _trans('common.Notifications'),
            'data' => $notification_collection,
        ];

        $support_tickets = SupportTicket::where(function ($q) {
            $q->where('user_id', auth()->user()->id);
            $q->orWhere('assigned_id', auth()->user()->id);
        })
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();
        $support_tickets_collection = $support_tickets->map(function ($support_ticket) {
            $date1 = date_create(date('Y-m-d'));
            $date2 = date_create($support_ticket->date);
            $diff = date_diff($date1, $date2);
            $days = $diff->format("%a");
            $days_count = $days - 3;
            $alert = '';
            $color = '';
            if ($days_count > 0) {
                $alert = 'Overdue by ' . $days_count . ' days';
                $color = '0xffC82024';
            } else {
                $alert = 'Due today';
                $color = '0xff1A4CB4';
            }
            return [
                'alert' => $alert,
                'client_alert' => $support_ticket->created_by->name,
                'title' => $support_ticket->subject,
                'description' => $support_ticket->description,
                'priority' => $support_ticket->priority->name,
                'code' => $support_ticket->code,
                'color' => $color,
            ];
        });
        $supports = [
            'slug' => 'notices',
            'label' => 'Pro',
            'data' => $support_tickets_collection,
        ];

        $calendar = [
            'slug' => 'notices',
            'label' => 'Pro',
            'data' => [
                'current_month' => date('M Y'),
                'selected_date' => date('d'),
            ],
        ];

        $projects = Project::with('members')
            ->whereHas('members', function ($q) {
                $q->where('user_id', auth()->user()->id);
            })
            ->where('status_id', 26)
            ->orderBy('id', 'DESC')
            ->take(5)
            ->get();
        $collection = [
            'project_summary' => $project_summary,
            'staticstics' => $staticstics,
            'tasks' => $tasks,
            'projects' => $this->ProjectListCollection($projects),
            'notices' => $notices,
            'supports' => $supports,
            'calendar' => $calendar,
        ];
        // $collection = [ $collection];
        return $this->responseWithSuccess('App Home Screen Menus', $collection, 200);
    }

    /* ************************************* start projects ************************************** */

    public function ProjectListCollection($projects)
    {
        return $projects->map(function ($project) {
           
            return [
                'id' => $project->id,
                'title' => $project->name,
                'date_range' => Carbon::parse($project->start_date)->format('D, j M Y') . '-' . Carbon::parse($project->end_date)->format('D, j M Y'),
                'users_count' => listCountStatus($project->members->count()),
                'actual_count' => $project->members->count(),
                'members' => $this->MemberListCollection($project->members->take(3)),
                'progress' => intval($project->progress),

                'color' => '0xff' . $project->status->color_code,
                'status' => $project->status->name,
                'priority' => $project->priorityStatus->name,
                'priority_color' => '0xff' . $project->priorityStatus->color_code,
                'clickable' => true,
                'end_point' => 'project/details/' . $project->id,
            ];
        });
    }

    public function ProjectListCollectionWithPagination($projects)
    {
        return [
            'projects' => $projects->map(function ($project) {

                return [
                    'id' => $project->id,
                    'title' => $project->name,
                    'date_range' => Carbon::parse($project->start_date)->format('D, j M Y') . '-' . Carbon::parse($project->end_date)->format('D, j M Y'),
                    'users_count' => listCountStatus($project->members->count()),
                    'actual_count' => $project->members->count(),
                    'members' => $this->MemberListCollection($project->members->take(3)),
                    // 'members' => $this->UserListCollection($project->members->take(3)), 
                    'progress' => $project->progress,

                    'color' => '0xff' . $project->status->color_code,
                    'status' => $project->status->name,
                    'priority' => $project->priorityStatus->name,
                    'priority_color' => '0xff' . $project->priorityStatus->color_code,
                    'clickable' => true,
                    'end_point' => 'project/details/' . $project->id,
                ];
            }),
            'pagination' => [
                'total' => $projects->total(),
                'count' => $projects->count(),
                'per_page' => $projects->perPage(),
                'current_page' => $projects->currentPage(),
                'total_pages' => $projects->lastPage(),
            ],
        ];
    }
    public function ClientProjectListCollectionWithPagination($projects)
    {
        return [
            'projects' => $projects->map(function ($project) {
                return [
                    'id' => $project->id,
                    'title' => $project->name,
                    'date' => Carbon::parse($project->start_date)->format('d M'),
                    'color' => "0xff" . $project->status->color_code,
                    'status' => $project->status->name,
                    // 'priority' => $project->priorityStatus->name,
                    'avatar' => @uploaded_asset($project->avatar_id),
                    'clickable' => true,
                    'end_point' => 'project/details/' . $project->id,
                ];
            }),
            'pagination' => [
                'total' => $projects->total(),
                'count' => $projects->count(),
                'per_page' => $projects->perPage(),
                'current_page' => $projects->currentPage(),
                'total_pages' => $projects->lastPage(),
            ],
        ];
    }

    public function GetAppProjectsScreen()
    {
        $projects = Project::with('members')
            ->whereHas('members', function ($q) {
                $q->where('user_id', auth()->user()->id);
            })
            ->orderBy('id', 'DESC')
            ->get();
        $staticstics = [
            [
                'count' => $projects->where('status_id', 25)->count(),
                'label' => 'On Hold',
                'image' => static_asset('public/assets/app/projects/alert.png'),
                'status' => 25,
            ],
            [
                'count' => $projects->where('status_id', 26)->count(),
                'label' => 'In Progress',
                'image' => static_asset('public/assets/app/projects/info.png'),
                'status' => 26,
            ],
            [
                'count' => $projects->where('status_id', 27)->count(),
                'label' => 'Completed',
                'image' => static_asset('public/assets/app/projects/success.png'),
                'status' => 27,
            ],
            [
                'count' => $projects->where('status_id', 28)->count(),
                'label' => 'Cancelled',
                'image' => static_asset('public/assets/app/projects/warning.png'),
                'status' => 28,
            ],
        ];
        $projects = $projects
        // ->where('status_id', 26)
        ->take(5);
        $collection = [
            'staticstics' => $staticstics,
            'projects' => $this->ProjectListCollection($projects),
        ];
        return $this->responseWithSuccess('Project Dashboard', $collection, 200);
    }

    public function GetAppProjectsList($request)
    {
        try {
            $projects = Project::with('members')
                ->whereHas('members', function ($q) {
                    $q->where('user_id', auth()->user()->id);
                })
                ->when($request->keyword, function ($q) use ($request) {
                    $q->where('projects.name', 'LIKE', '%' . $request->keyword . '%');
                })
                ->when($request->status_id, function ($q) use ($request) {
                    $q->where('projects.status_id', $request->status_id);
                })
                ->when($request->priority_id, function ($q) use ($request) {
                    $q->where('projects.priority', $request->priority_id);
                })
                ->orderBy('id', 'DESC')
                ->paginate(10);
            $priorities = [
                [
                    'title' => _trans('tasks.Urgent'),
                    'id' => 29,
                ],
                [
                    'title' => _trans('tasks.High'),
                    'id' => 30,
                ],
                [
                    'title' => _trans('tasks.Medium'),
                    'id' => 31,
                ],
                [
                    'title' => _trans('tasks.Low'),
                    'id' => 32,
                ],
            ];
            $collection = [
                'slug' => 'projects',
                'label' => 'Pro',
                'priorities' => $priorities,
                'data' => $this->ProjectListCollectionWithPagination($projects),
            ];
            return $this->responseWithSuccess('Project List ', $collection, 200);
        } catch (\Throwable $th) {
            return $this->responseWithError('Something went wrong', 500);
        }
    }
    public function AppProjectsDetails($project_id)
    {

        try {
            $project = Project::find($project_id);
            if (!$project) {
                return $this->responseWithError('Project not found', 404);
            }
            $collection = [
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'start_date' =>  Carbon::parse($project->start_date)->format('d M Y'),
                'end_date' =>  Carbon::parse($project->end_date)->format('d M Y'),
             
                'status' => $project->status->name,

                'users_count' => listCountStatus($project->members->count()),
                'actual_count' => $project->members->count(),
                'client' => [
                    'id' => @$project->client->id,
                    'name' => @$project->client->name,
                    'avatar' => @uploaded_asset($project->client->avatar_id),
                ],
                'members' => $this->MemberListCollection( $project->members_short_list),
                'project_owner' => [
                    'id' => @$project->client->id,
                    'name' => @$project->client->name,
                    'avatar' => @uploaded_asset(@$project->client->avatar_id),
                ],
                'timeline' => Carbon::parse($project->start_date)->format('d M'),
                'progress' => intval($project->progress),
                'tasks' => $project->tasks->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'name' => $task->name,
                        'is_completed' => $task->status_id == 27 ? true : false,
                        'date' => Carbon::parse($task->end_date)->format('d M'),
                        'members' => $this->MemberListCollection($task->members_short),
                        'discussions' => $task->discussions->count(),
                        'files' => $task->files->count(),
                    ];
                }),
                'feedback' => $project->tasks->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'name' => $task->name,
                        'is_completed' => $task->status_id == 27 ? true : false,
                        'date' => Carbon::parse($task->end_date)->format('d M'),
                        'discussions_count' => $task->discussions->count(),
                        'files_count' => $task->files->count(),
                        'members' => $this->MemberListCollection($task->members_short),
                        'discussions' => $task->discussions->map(function ($discussion) {
                            return [
                                'id' => $discussion->id,
                                'subject' => $discussion->subject,
                                'description' => $discussion->description,
                                'created_by' => $discussion->user->name,
                                'avatar' => @uploaded_asset($discussion->user->avatar_id),
                                'created_at' => Carbon::parse($discussion->created_at)->diffForHumans(),
                                'already_liked' => $discussion->likes->contains('user_id', Auth::id()),
                                'likes_count' => $discussion->likes->count(),
                                'own_created' => $discussion->user_id == auth()->user()->id ? true : false,
                                'file' => $discussion->file_id ? @uploaded_asset($discussion->file_id) : '',
                            ];
                        }),
                        'files' => $task->files->map(function ($file) {
                            return [
                                'id' => $file->id,
                                'attachment' => uploaded_asset($file->attachment),
                                'file_logo' => file_logo(pathinfo(uploaded_asset($file->attachment), PATHINFO_EXTENSION)),
                                'title' => Str::replace(' ', '-', $file->subject) . '.' . pathinfo(uploaded_asset($file->attachment), PATHINFO_EXTENSION),
                                'type' => getFileType(pathinfo(uploaded_asset($file->attachment), PATHINFO_EXTENSION)),
                                'created_by' => $file->user->name,
                            ];
                        }),
                    ];
                }),
                'files' => $project->files->map(function ($file) {
                    return [
                        'id' => $file->id,
                        'attachment' => uploaded_asset($file->attachment),
                        'file_logo' => file_logo(pathinfo(uploaded_asset($file->attachment), PATHINFO_EXTENSION)),
                        'title' => Str::replace(' ', '-', $file->subject) . '.' . pathinfo(uploaded_asset($file->attachment), PATHINFO_EXTENSION),
                        'type' => getFileType(pathinfo(uploaded_asset($file->attachment), PATHINFO_EXTENSION)),
                        'created_by' => $file->user->name,
                    ];
                }),

            ];
            return $this->responseWithSuccess('Project Details ', $collection, 200);
        } catch (\Throwable $th) {
            return $this->responseWithError('Something went wrong', 500);
        }
    }
    public function AppProjectsDelete($project_id)
    {

        try {
            $project = Project::find($project_id);
            if (!$project) {
                return $this->responseWithError('Project Not Found', 404);
            }
            $project->delete();
            return $this->responseWithSuccess('Project Deleted ', [], 200);
        } catch (\Throwable $th) {
            return $this->responseWithError('Something went wrong', 500);
        }
    }
    public function AssignUserToProject($project_id, $user_id)
    {
        $project = Project::where('id', $project_id)->first();
        if (!$project) {
            return $this->responseWithError('Project Not Found', 404);
        }
        try {
            $project_membar = new ProjectMembar();
            $project_membar->company_id = auth()->user()->company_id;
            $project_membar->added_by = auth()->user()->id;
            $project_membar->project_id = $project_id;
            $project_membar->user_id = $user_id;
            $project_membar->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function AppProjectsStore($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'client' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            DB::beginTransaction();
            $project = new Project();
            $project->name = $request->name;
            $project->description = $request->description;
            $project->start_date = $request->start_date;
            $project->end_date = $request->end_date;
            $project->date = $request->start_date;
            $project->date = $request->start_date;
            $project->client_id = $request->client;
            $project->created_by = auth()->user()->id;
            $project->save();

            $this->AssignUserToProject($project->id, auth()->user()->id);

            $membars = explode(',', $request->membars);
            foreach ($membars as $key => $membar) {
                $this->AssignUserToProject($project->id, $membar);
            }
            DB::commit();
            return $this->responseWithSuccess('Project Created', [], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseWithError($th->getMessage(), 500);
        }
    }
    public function AppProjectsUpdate($request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'client' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            DB::beginTransaction();
            $project = Project::find($request->id);
            $project->name = $request->name;
            $project->description = $request->description;
            $project->start_date = $request->start_date;
            $project->end_date = $request->end_date;
            $project->date = $request->start_date;
            $project->date = $request->start_date;
            $project->client_id = $request->client;
            $project->save();

            $project->members()->delete();

            $membars = explode(',', $request->membars);
            foreach ($membars as $key => $membar) {
                $this->AssignUserToProject($project->id, $membar);
            }
            DB::commit();
            return $this->responseWithSuccess('Project Updated', [], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseWithError($th->getMessage(), 500);
        }
    }
    public function AppProjectsChangeStatus($request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required',
            'type' => 'required',
            'change_to' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            $task = Project::find($request->task_id);
            if ($request->type == '1') {
                $task->status_id = $request->change_to;
            } else {
                $task->priority = $request->change_to;
            }
            $task->save();
            return $this->responseWithSuccess('Task Status Changed', [], 200);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), 422);
        }
    }
    public function AppProjectsFileUpload($request)
    {
        //validation check
        $validator = Validator::make($request->all(), [
            'file_for' => 'required',
            'id' => 'required',
            'subject' => 'required',
            'file' => 'required|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,png,jpg,jpeg|max:2048',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            if ($request->file_for == '1') {
                $project_file = new ProjectFile();
                $project_file->project_id = $request->id;
                $project_file->subject = $request->subject;
                $project_file->show_to_customer = 32;
                $project_file->company_id = auth()->user()->company_id;
                $project_file->user_id = auth()->user()->id;
                $project_file->save();

                if ($request->hasFile('file')) {
                    $filePath = $this->uploadImage($request->file, 'uploads/employeeDocuments');
                    $project_file->attachment = $filePath ? $filePath->id : null;
                    $project_file->save();
                }
            } else {
                $task_file = new TaskFile();
                $task_file->task_id = $request->id;
                $task_file->subject = $request->subject;
                $task_file->show_to_customer = 32;
                $task_file->company_id = auth()->user()->company_id;
                $task_file->user_id = auth()->user()->id;
                $task_file->save();

                if ($request->hasFile('file')) {
                    $filePath = $this->uploadImage($request->file, 'uploads/employeeDocuments');
                    $task_file->attachment = $filePath ? $filePath->id : null;
                    $task_file->save();
                }
            }
            return $this->responseWithSuccess('File Uploaded', [], 200);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), 500);
        }
    }

    /************************\ end projects /************************/

    /************************\ strat clients /************************/

    public function ClientListCollectionWithPagination($clients)
    {
        return [
            'clients' => $clients->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'email' => $data->email,
                    'phone' => $data->phone,
                    'avater' => @uploaded_asset($data->avatar_id),
                ];
            }),
            'pagination' => [
                'total' => $clients->total(),
                'count' => $clients->count(),
                'per_page' => $clients->perPage(),
                'current_page' => $clients->currentPage(),
                'total_pages' => $clients->lastPage(),
            ],
        ];
    }
    public function ClientListCollection($clients)
    {
        return $clients->map(function ($data) {
            return [
                'id' => $data->id,
                'name' => $data->name,
                'email' => $data->email,
                'phone' => $data->phone,
                'avater' => @uploaded_asset($data->avatar_id),
            ];
        });
    }

    public function GetAppClientsScreen()
    {
        $clients = Client::OrderBy('id', 'DESC')->take(5)->select('id', 'name', 'email', 'phone', 'status_id', 'avatar_id')->take(6)->get();
        $all_clients = Client::get();
        $collection = [
            'staticstics' => [
                [
                    'count' => '' . $all_clients->count(),
                    'text' => _trans('clients.Total clients'),
                    'image' => static_asset('public/assets/app/clients/1.png'),
                ],
                [
                    'count' => '' . $all_clients->where('status_id', 1)->count(),
                    'text' => _trans('clients.Active clients'),
                    'image' => static_asset('public/assets/app/clients/2.png'),
                ],

                [
                    'count' => '' . $all_clients->where('status_id', 4)->count(),
                    'text' => _trans('clients.Inactive clients'),
                    'image' => static_asset('public/assets/app/clients/3.png'),
                ],

                [
                    'count' => '' . $all_clients->where('status_id', 33)->count(),
                    'text' => _trans('clients.Contact clients'),
                    'image' => static_asset('public/assets/app/clients/4.png'),
                ],

            ],
            'clients' => $this->ClientListCollection($clients),
        ];
        return $this->responseWithSuccess('App Clients Screen', $collection, 200);
    }

    public function GetAppClientsList($request)
    {
        if (@$request->keyword !== null) {
            $clients = Client::where('name', 'LIKE', '%' . $request->keyword . '%')
                ->OrderBy('id', 'DESC')
                ->select('id', 'name', 'email', 'phone', 'status_id', 'avatar_id')
                ->paginate(10);
        } else {
            $clients = Client::OrderBy('id', 'DESC')
                ->select('id', 'name', 'email', 'phone', 'status_id', 'avatar_id')
                ->paginate(10);
        }
        $collection = $this->ClientListCollectionWithPagination($clients);
        return $this->responseWithSuccess('Clients List', $collection, 200);
    }
    public function AppClientsDetails($request)
    {
        $client = Client::with('projects')->where('id', $request->id)->first();
        if (!$client) {
            return $this->responseWithError('Client Not Found', 404);
        }
        $client_projects = $client->projects_short_list;
        $collection = [
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
                'address' => $client->address,
                'description' => $client->description,
                'avater' => @uploaded_asset($client->avatar_id),
                'project_statistics' => [
                    [
                        'title' => _trans('projects.Not Started'),
                        'status_id' => 24,
                        'count' => $client_projects->where('status_id', 24)->count(),
                    ],
                    [
                        'title' => _trans('projects.In Progress'),
                        'status_id' => 26,
                        'count' => $client_projects->where('status_id', 26)->count(),
                    ],
                    [
                        'title' => _trans('projects.Completed'),
                        'status_id' => 27,
                        'count' => $client_projects->where('status_id', 27)->count(),
                    ],
                    [
                        'title' => _trans('projects.Cancelled'),
                        'status_id' => 28,
                        'count' => $client_projects->where('status_id', 28)->count(),
                    ],
                ],
                'projects' => $client_projects->map(function ($data) {
                    return [
                        'id' => $data->id,
                        'name' => $data->name,
                        'status' => $data->status->name,
                        'status_id' => $data->status_id,
                        'date' => Carbon::parse($data->start_date)->format('d M'),
                        'avatar' => @uploaded_asset($data->avatar_id),
                        'clickable' => true,
                        'end_point' => 'project/details/' . $data->id,
                        'color' => "0xff" . $data->status->color_code,
                    ];
                }),
            ],
        ];
        return $this->responseWithSuccess('Clients Details', $collection, 200);
    }
    public function AppClientsDelete($id)
    {
        $client = Client::find($id);
        if (!$client) {
            return $this->responseWithError('Client Not Found', 404);
        }
        $client->delete();
        return $this->responseWithSuccess('Client Deleted', [], 200);
    }
    public function AppClientsProjects($request, $client_id)
    {
        $client = Client::where('id', $client_id)->first();
        if (!$client) {
            return $this->responseWithError('Client Not Found', 404);
        }
        $projects = Project::where('client_id', $client_id)
            ->when($request->keyword, function ($query) use ($request) {
                return $query->where('name', 'LIKE', '%' . $request->keyword . '%');
            })
            ->paginate(10);

        $client_projects = $this->ClientProjectListCollectionWithPagination($projects);

        return $this->responseWithSuccess('Clients Details', $client_projects, 200);
    }
    public function AppClientsStore($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'required|unique:clients,phone',
            'address' => 'required',
            'description' => 'required',
            'avatar' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            DB::beginTransaction();
            $client = new Client();
            $client->name = $request->name;
            $client->email = $request->email;
            $client->phone = $request->phone;
            $client->address = $request->address;
            $client->description = $request->description;
            $client->save();
            if ($request->hasFile('avatar')) {
                $filePath = $this->uploadImage($request->avatar, 'uploads/avatar');
                $client->avatar_id = $filePath ? $filePath->id : null;
                $client->save();
            }
            DB::commit();
            return $this->responseWithSuccess('Client Created', [], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseWithError($th->getMessage(), 500);
        }
    }
    public function AppClientsUpdate($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'id',
            'name' => 'required',
            'email' => 'required|email|unique:clients,email,' . $request->id,
            'phone' => 'required|unique:clients,phone,' . $request->id,
            'address' => 'required',
            'description' => 'required',
            'avatar' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            DB::beginTransaction();
            $client = Client::find($request->id);
            $client->name = $request->name;
            $client->email = $request->email;
            $client->phone = $request->phone;
            $client->address = $request->address;
            $client->description = $request->description;
            $client->save();
            if ($request->hasFile('avatar')) {
                $filePath = $this->uploadImage($request->avatar, 'uploads/avatar');
                $client->avatar_id = $filePath ? $filePath->id : null;
                $client->save();
            }
            DB::commit();
            return $this->responseWithSuccess('Client Updated', [], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseWithError($th->getMessage(), 500);
        }
    }

    /************************\ end clients /************************/

    /************************\ strat employees /************************/

    public function UserListCollectionWithPagination($employees)
    {
        return [
            'employees' => $employees->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'phone' => $data->phone,
                    'email' => $data->email,
                    'designation' => @$data->designation->title,
                    'avatar' => uploaded_asset($data->avatar_id),
                ];
            }),
            'pagination' => [
                'total' => $employees->total(),
                'count' => $employees->count(),
                'per_page' => $employees->perPage(),
                'current_page' => $employees->currentPage(),
                'total_pages' => $employees->lastPage(),
            ],
        ];
    }

    public function UserListCollection($employees)
    {
        return $employees->map(function ($data) {
            return [
                'id' => $data->id,
                'name' => $data->name,
                'phone' => $data->phone,
                'email' => $data->email,
                'designation' => @$data->designation->title,
                'department' => @$data->department->title,
                'avatar' => uploaded_asset($data->avatar_id),
            ];
        });
    }


    public function MemberListCollection($members)
    {
        return $members->map(function ($data) {
            return [
                'id' => $data->user->id,
                'name' => $data->user->name,
                'phone' => $data->user->phone,
                'email' => $data->user->email,
                'designation' => @$data->user->designation->title,
                'department' => @$data->user->department->title,
                'avatar' => uploaded_asset($data->user->avatar_id),
            ];
        });
    }


    public function GetAppEmployeeList($request)
    {
        if (@$request->keyword !== null) {
            $employees = User::where('name', 'LIKE', '%' . $request->keyword . '%')
                ->orderBy('id', 'desc')
                ->paginate(20);
        } else {
            $employees = User::orderBy('id', 'desc')->paginate(20);
        }
        $collection = $this->UserListCollectionWithPagination($employees);
        return $this->responseWithSuccess('Employee List', $collection, 200);
    }
    public function AppEmployeeDetails($request)
    {
        if (isset($request->employee_id)) {
            $employee_id = $request->employee_id;
        } else {
            $employee_id = auth()->user()->id;
        }
        $employee = User::find($employee_id);
        if ($employee) {
            $collection = [
                'id' => $employee->id,
                'name' => $employee->name,
                'designation' => @$employee->designation->title,
                'department' => @$employee->department->title,
                'address' => $employee->address,
                'phone' => $employee->phone,
                'email' => $employee->email,
                'about_me' => $employee->about_me,
                'avatar' => uploaded_asset($employee->avatar_id),
                'skills' => $employee->skills->map(function ($data) {
                    return [
                        'id' => $data->id,
                        'name' => $data->name,
                        'percentage' => $data->percentage,
                    ];
                }),
            ];
            return $this->responseWithSuccess('Employee Details', $collection, 200);
        } else {
            return $this->responseWithError('Employee Not Found', 404);
        }
    }
    public function AppEmployeeDelete($employee_id)
    {
        $employee = User::find($employee_id);
        if ($employee) {
            $employee->delete();
            return $this->responseWithSuccess('Employee Delete', [], 200);
        } else {
            return $this->responseWithError('Employee Not Found', 404);
        }
    }
    public function AppEmployeeStore($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required',
            'designation' => 'required',
            'department' => 'required',
            'location' => 'required',
            'skills' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            DB::beginTransaction();
            $staff_role = Role::where('slug', 'staff')->first();

            $employee = new User();
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            $employee->password = Hash::make($request->password);
            $employee->designation_id = $request->designation;
            $employee->department_id = $request->department;
            $employee->address = $request->location;
            $employee->about_me = $request->about_me;
            $employee->role_id = $staff_role->id;
            $employee->company_id = auth()->user()->company_id;
            $employee->country_id = auth()->user()->country_id;
            $employee->save();
            if ($request->skills) {
                $skills = explode(',', $request->skills);
                foreach ($skills as $user_skill) {
                    $skill = new Skill();
                    $skill->name = $user_skill;
                    $skill->user_id = $employee->id;
                    $skill->save();
                }
            }
            if ($request->hasFile('avatar')) {
                $filePath = $this->uploadImage($request->avatar, 'uploads/avatar');
                $employee->avatar_id = $filePath ? $filePath->id : null;
                $employee->save();
            }
            DB::commit();
            return $this->responseWithSuccess('Employee Created Successfully', [], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseWithError($th->getMessage(), 500);
        }
    }
    public function AppEmployeeUpdate($request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'phone' => 'required|unique:users,phone,' . $request->id,
            'designation' => 'required',
            'department' => 'required',
            'location' => 'required',
            'skills' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            DB::beginTransaction();
            $employee = User::find($request->id);
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            if ($request->password) {
                $employee->password = Hash::make($request->password);
            }
            $employee->designation_id = $request->designation;
            $employee->department_id = $request->department;
            $employee->address = $request->location;
            $employee->role_id = 4;
            $employee->company_id = auth()->user()->company_id;
            $employee->country_id = auth()->user()->country_id;
            $employee->about_me = $request->about_me;
            $employee->save();
            if ($request->skills) {
                $employee->skills()->delete();
                $skills = explode(',', $request->skills);
                foreach ($skills as $user_skill) {
                    $skill = new Skill();
                    $skill->name = $user_skill;
                    $skill->user_id = $employee->id;
                    $skill->save();
                }
            }
            if ($request->hasFile('avatar')) {
                $filePath = $this->uploadImage($request->avatar, 'uploads/avatar');
                $employee->avatar_id = $filePath ? $filePath->id : null;
                $employee->save();
            }
            DB::commit();
            return $this->responseWithSuccess('Employee Updated Successfully', [], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseWithError($th->getMessage(), 500);
        }
    }

    public function GetAppEmployeesScreen()
    {
        $users = User::OrderBy('id', 'ASC')
            ->take(5)->select('id', 'name', 'email', 'status_id', 'phone', 'department_id', 'designation_id', 'avatar_id')
            ->where('is_admin', '!=', 1)
            ->get();
        $collection = [
            'staticstics' => [
                [
                    'count' => '' . $users->count(),
                    'text' => _trans('employees.Total employees'),
                    'image' => static_asset('public/assets/app/employees/total.png'),
                ],
                [
                    'count' => '' . $users->where('status_id', 1)->count(),
                    'text' => _trans('employees.Active employees'),
                    'image' => static_asset('public/assets/app/employees/active.png'),
                ],

                [
                    'count' => '' . $users->where('status_id', 2)->count(),
                    'text' => _trans('employees.Inactive employees'),
                    'image' => static_asset('public/assets/app/employees/inactive.png'),
                ],

                [
                    'count' => '' . $users->where('status_id', 34)->count(),
                    'text' => _trans('employees.Hiring employees'),
                    'image' => static_asset('public/assets/app/employees/contact.png'),
                ],

            ],
            'employees' => $this->UserListCollection($users),
        ];
        return $this->responseWithSuccess('Employee Dashboard', $collection, 200);
    }

    /************************\ end employees /************************/

    public function GetAppSalesScreen()
    {
        $staticstics = [
            'Orders' => [
                'number' => 1200,
                'title' => 'Orders',
            ],
            'Gross' =>
            [
                'number' => 1200,
                'title' => 'Gross',
            ],
            'Pending' =>
            [
                'number' => 1200,
                'title' => 'Pending',
            ],
        ];

        $charts = [
            'total_order' => 20,
            'new_order' => 30,
            'delivered' => 50,
        ];

        $latest_sales = [
            [
                'id' => 1,
                'image' => static_asset('public/assets/app/projects/user1.png'),
                'titile' => 'Dell Laptop',
                'user' => 'Demo 01',
                'payment' => 'PayPal',
                'invoice_number' => '#6493',
                'price' => 989898.50,

            ],
            [
                'id' => 1,
                'image' => static_asset('public/assets/app/projects/user2.png'),
                'titile' => 'Hp Laptop',
                'user' => 'Demo 02',
                'payment' => 'PayPal',
                'invoice_number' => '#6494',
                'price' => 50989.50,
            ],
            [
                'id' => 1,
                'image' => static_asset('public/assets/app/projects/user3.png'),
                'titile' => 'Hp Laptop',
                'user' => 'Demo 03',
                'payment' => 'PayPal',
                'invoice_number' => '#6494',
                'price' => 50989.50,
            ],
        ];
        $collection = [
            'staticstics' => $staticstics,
            'charts' => $charts,
            'latest_sales' => $latest_sales,
        ];

        return $this->responseWithSuccess('App Project Screen', $collection, 200);
    }

    public function GetAppStockScreen()
    {

        $staticstics = [
            [
                'count' => StockBrand::where('status_id', 1)->count(),
                'text' => _trans('stock.Brands'),
                'image' => static_asset('public/assets/app/tasks/1.png'),
            ],
            [
                'count' => StockCategory::where('status_id', 1)->count(),
                'text' => _trans('common.Category'),
                'image' => static_asset('public/assets/app/tasks/2.png'),
            ],

            [
                'count' => StockProduct::where('status_id', 1)->count(),
                'text' => _trans('stock.Products'),
                'image' => static_asset('public/assets/app/tasks/3.png'),
            ],

            [
                'count' => 29,
                'text' => _trans('stock.Stock Out'),
                'image' => static_asset('public/assets/app/tasks/4.png'),
            ],
        ];

        $stock_categories = StockCategory::take(5)->get();
        $category_collection = $this->BrandCategoryList($stock_categories);
        $categories = $category_collection['items'];
        $stock_brands = StockBrand::take(5)->get();
        $brand_collection = $this->BrandCategoryList($stock_brands);
        $stores = $brand_collection['items'];

        $stock_products = StockProduct::take(5)->get();
        $product_collection = $this->StockProductList($stock_products);
        $products = $product_collection['items'];

        $collection = [
            'staticstics' => $staticstics,
            'categories' => $categories,
            'products' => $products,
            'stores' => $stores,
        ];

        return $this->responseWithSuccess('App Stock Screen', $collection, 200);
    }
    public function AppPurchaseProducts()
    {

        $staticstics = [
            [
                'count' => StockBrand::where('status_id', 1)->count(),
                'text' => _trans('stock.Brands'),
                'image' => static_asset('public/assets/app/tasks/1.png'),
            ],
            [
                'count' => StockCategory::where('status_id', 1)->count(),
                'text' => _trans('common.Category'),
                'image' => static_asset('public/assets/app/tasks/2.png'),
            ],

            [
                'count' => StockProduct::where('status_id', 1)->count(),
                'text' => _trans('stock.Products'),
                'image' => static_asset('public/assets/app/tasks/3.png'),
            ],

            [
                'count' => 29,
                'text' => _trans('stock.Stock Out'),
                'image' => static_asset('public/assets/app/tasks/4.png'),
            ],
        ];

        $stock_categories = StockCategory::take(5)->get();
        $category_collection = $this->BrandCategoryList($stock_categories);
        $categories = $category_collection['items'];
        $stock_brands = StockBrand::take(5)->get();
        $brand_collection = $this->BrandCategoryList($stock_brands);
        $stores = $brand_collection['items'];

        $stock_products = StockProduct::take(5)->get();
        $product_collection = $this->StockProductList($stock_products);
        $products = $product_collection['items'];

        $collection = [
            'staticstics' => $staticstics,
            'categories' => $categories,
            'products' => $products,
            'stores' => $stores,
        ];

        return $this->responseWithSuccess('App Purchase Screen', $collection, 200);
    }

    public function taskPriorityColor($priority)
    {
        switch ($priority) {
            case 29:
                return '0xff5B58FF';
                break;
            case 30:
                return '0xffFC990F';
                break;
            case 31:
                return '0xffE96161';
                break;
            default:
                return '0xff5B58FF';
                break;
        }
    }

    public function TasksCollection($tasks)
    {

        return $tasks->map(function ($data) {

            return [
                'id' => @$data->task->id,
                'title' => @$data->task->name,
                'date_range' => Carbon::parse($data->task->start_date)->format('d M') . '-' . Carbon::parse($data->task->end_date)->format('d M'),
                'start_date' => $data->task->start_date,
                'end_date' => $data->task->end_date,
                'priority' => $data->task->priority,
                'is_creator' => $data->task->created_by == Auth::id() ? true : false,
                'users_count' => listCountStatus($data->task->members->count()),
                'actual_count' => $data->task->members->count(),
                'members' => $this->MemberListCollection($data->task->members_short),
                'color' => $this->taskPriorityColor($data->task->priority),
            ];
        });
    }
    public function TaskListCollectionWithPagination($tasks)
    {
        return [
            'tasks' => $tasks->map(function ($data) {
                return [
                    'id' => @$data->task->id,
                    'title' => @$data->task->name,
                    'date_range' => Carbon::parse($data->task->start_date)->format('d M') . '-' . Carbon::parse($data->task->end_date)->format('d M'),
                    'start_date' => $data->task->start_date,
                    'end_date' => $data->task->end_date,
                    'priority' => $data->task->priority,
                    'is_creator' => $data->task->created_by == Auth::id() ? true : false,
                    'users_count' => listCountStatus($data->task->members->count()),
                    'actual_count' => $data->task->members->count(),

                'members' => $this->MemberListCollection($data->task->members_short),
                    'color' => $this->taskPriorityColor($data->task->priority),
                ];
            }),
            'pagination' => [
                'total' => $tasks->total(),
                'count' => $tasks->count(),
                'per_page' => $tasks->perPage(),
                'current_page' => $tasks->currentPage(),
                'total_pages' => $tasks->lastPage(),
            ],
        ];
    }

    //Tasks Api
    public function AppTaskDetails($request, $task_id)
    {
        try {
            $task = Task::find($task_id);
            if ($task) {
                $clients = Client::where('status_id', 1)->where('id', $task->client_id)->select('id', 'name', 'avatar_id')->get();
                $projects = Project::where('status_id', 26)->where('id', $task->project_id)->select('id', 'name')->get();
                $users = $task->members->map(function ($data) {
                    return [
                        'id' => $data->id,
                        'name' => $data->name,
                        'designation' => @$data->designation->title,
                        'department' => @$data->department->title,
                        'avatar' => uploaded_asset($data->avatar_id),
                    ];
                });
                $clients_collection = $clients->map(function ($data) {
                    return [
                        'id' => $data->id,
                        'name' => $data->name,
                        'avatar' => uploaded_asset($data->avatar_id),
                    ];
                });
                $projects_collection = $projects->map(function ($data) {
                    return [
                        'id' => $data->id,
                        'name' => $data->name,
                    ];
                });
                $users_collection = $task->members->map(function ($member) {
                    return [
                        'id' => $member->user->id,
                        'name' => $member->user->name,
                        'avatar' => uploaded_asset($member->user->avatar_id),
                        'email' => $member->user->email,
                        'phone' => $member->user->phone,
                        'designation' => $member->user->designation->title,
                    ];
                });

                $priorities = [
                    [
                        'title' => _trans('common.' . $task->priorityStatus->name),
                        'id' => $task->priorityStatus->id,
                    ],
                ];

                $collection = [
                    'id' => $task->id,
                    'title' => $task->name,
                    'db_start_date' => $task->start_date,
                    'start_date' => Carbon::parse($task->start_date)->format('d M Y'),
                    'db_end_date' => $task->end_date,
                    'end_date' => Carbon::parse($task->end_date)->format('d M Y'),
                    'date' => Carbon::parse($task->end_date)->format('d M'),
                    'supervisor' => @$task->createdBy->name,

                    'users_count' => listCountStatus($task->members->count()),
                    'actual_count' => $task->members->count(),

                    'discussions_count' => $task->discussions->count(),
                    'files_count' => $task->files->count(),
                    'progress' => intval($task->progress),
                    'project_id' => $task->project->id,
                    'project' => $task->project->name,
                    'client_id' => @$task->client->id,
                    'client' => @$task->client->name,
                    'is_completed' => $task->status_id == 27 ? true : false,
                    'priority_id' => $task->priorityStatus->id,
                    'priority' => _trans('common.' . $task->priorityStatus->name),

                    // 'members' => $this->UserListCollection($task->members), 
                    'color' => $this->taskPriorityColor($task->priority),
                    'description' => $task->description,

                    'files' => $task->files->map(function ($file) {
                        return [
                            'id' => $file->id,
                            'attachment' => uploaded_asset($file->attachment),
                            'file_logo' => file_logo(pathinfo(uploaded_asset($file->attachment), PATHINFO_EXTENSION)),
                            'title' => Str::replace(' ', '-', $file->subject) . '.' . pathinfo(uploaded_asset($file->attachment), PATHINFO_EXTENSION),
                            'type' => getFileType(pathinfo(uploaded_asset($file->attachment), PATHINFO_EXTENSION)),
                            'created_by' => $file->user->name,
                        ];
                    }),
                    'discussion' => $task->discussions->map(function ($discussion) {
                        return [
                            'id' => $discussion->id,
                            'subject' => $discussion->subject,
                            'description' => $discussion->description,
                            'created_by' => $discussion->user->name,
                            'avatar' => @uploaded_asset($discussion->user->avatar_id),
                            'created_at' => Carbon::parse($discussion->created_at)->diffForHumans(),
                            'already_liked' => $discussion->likes->contains('user_id', Auth::id()),
                            'likes_count' => $discussion->likes->count(),
                            'own_created' => $discussion->user_id == auth()->user()->id ? true : false,
                            'file' => $discussion->file_id ? @uploaded_asset($discussion->file_id) : '',
                        ];
                    }),
                ];
                $data = [
                    'clients' => $clients_collection,
                    'projects' => $projects_collection,
                    'members' => $this->MemberListCollection($task->members), 
                    'priorities' => $priorities,
                    'task_details' => $collection,
                ];
                return $this->responseWithSuccess('Task Details', $data, 200);
            } else {
                return $this->responseWithError('Task not found', [], 404);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError('Something went wrong', [], 500);
        }
    }
    public function AppTaskCreate()
    {
        try {
            $clients = Client::where('status_id', 1)->select('id', 'name', 'avatar_id')->get();
            $projects = Project::whereIn('status_id', [24, 25, 26, 27, 28])->select('id', 'name', 'status_id')->latest()->get();
            $users = User::where('status_id', 1)
                ->whereNotIn('id', [1, auth()->user()->id])
                ->select('id', 'name', 'avatar_id', 'email', 'phone', 'designation_id')
                ->get();
            $clients_collection = $clients->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'avatar' => uploaded_asset($data->avatar_id),
                ];
            });
            $projects_collection = $projects->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'status' => $data->status->name,
                    'status_id' => $data->status_id,
                ];
            });
            $users_collection = $users->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'avatar' => uploaded_asset($data->avatar_id),
                    'email' => $data->email,
                    'phone' => $data->phone,
                    'designation' => $data->designation->title,
                ];
            });

            $priorities = [
                [
                    'title' => _trans('tasks.Urgent'),
                    'id' => 29,
                ],
                [
                    'title' => _trans('tasks.High'),
                    'id' => 30,
                ],
                [
                    'title' => _trans('tasks.Medium'),
                    'id' => 31,
                ],
            ];
            $data = [
                'clients' => $clients_collection,
                'projects' => $projects_collection,

                'members' => $this->UserListCollection($users), 
                // 'members' => $users_collection,
                'priorities' => $priorities,
            ];
            return $this->responseWithSuccess('App Task Create', $data, 200);
        } catch (\Exception $e) {
        }
    }
    public function AppTaskStore($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'priority' => 'required',
            'project' => 'required',
            'client' => 'required',
            'members' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            $task = new Task();
            $task->name = $request->name;
            $task->date = $request->start_date;
            $task->start_date = $request->start_date;
            $task->end_date = $request->end_date;
            $task->priority = $request->priority;
            $task->project_id = $request->project;
            $task->client_id = $request->client;
            $task->description = $request->description;
            $task->status_id = 26;
            $task->created_by = auth()->user()->id;
            $task->save();
            $creator_member = new TaskMember();
            $creator_member->task_id = $task->id;
            $creator_member->user_id = auth()->user()->id;
            $creator_member->added_by = auth()->user()->id;
            $creator_member->save();
            $members = $request->members;
            $members = explode(',', $members);

            //Creator member
            if(!in_array(auth()->user()->id, $members)){
                $task_member = new TaskMember();
                $task_member->task_id = $task->id;
                $task_member->user_id = Auth::id();
                $task_member->added_by = Auth::id();
                $task_member->save();
            }
            foreach ($members as $member) {
                $task_member = new TaskMember();
                $task_member->task_id = $task->id;
                $task_member->user_id = $member;
                $task_member->added_by = Auth::id();
                $task_member->save();
            }
            return $this->responseWithSuccess('App Task Store', $task, 200);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), 422);
        }
    }
    public function AppTaskUpdate($request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'priority' => 'required',
            'project' => 'required',
            'client' => 'required',
            'members' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            $task = Task::find($request->id);
            $task->name = $request->name;
            $task->date = $request->start_date;
            $task->start_date = $request->start_date;
            $task->end_date = $request->end_date;
            $task->priority = $request->priority;
            $task->project_id = $request->project;
            $task->client_id = $request->client;
            $task->description = $request->description;
            $task->save();

            $task->members()->delete();

            //Creator member
            $task_member = new TaskMember();
            $task_member->task_id = $task->id;
            $task_member->user_id = Auth::id();
            $task_member->added_by = Auth::id();
            $task_member->save();

            $members = $request->members;
            if(!in_array(auth()->user()->id, $members)){
                $task_member = new TaskMember();
                $task_member->task_id = $task->id;
                $task_member->user_id = Auth::id();
                $task_member->added_by = Auth::id();
                $task_member->save();
            }
            $members = explode(',', $members);
            foreach ($members as $member) {
                $task_member = new TaskMember();
                $task_member->task_id = $task->id;
                $task_member->user_id = $member;
                $task_member->added_by = Auth::id();
                $task_member->save();
            }

            return $this->responseWithSuccess('App Task Store', $task, 200);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), 422);
        }
    }
    public function AppTaskStoreComment($request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required',
            // 'subject' => 'required',
            'comment' => 'required',
            'file' => 'mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx,txt|max:2048',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            $task = new TaskDiscussion();
            $task->company_id = auth()->user()->company_id;
            $task->subject = $request->subject;
            $task->description = $request->comment;
            $task->task_id = $request->task_id;
            $task->user_id = auth()->user()->id;
            $task->save();
            if ($request->hasFile('file')) {
                $filePath = $this->uploadImage($request->file, 'uploads/employeeDocuments');
                $task->file_id = $filePath ? $filePath->id : null;
                $task->save();
            }
            return $this->responseWithSuccess('Task Discussion Store', $task, 200);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), 422);
        }
    }
    public function AppTaskDeleteComment($id)
    {

        try {
            $task = TaskDiscussion::find($id);
            $task->delete();
            return $this->responseWithSuccess('Task Discussion Deleted', [], 200);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), 422);
        }
    }
    public function AppTaskUpdateComment($request)
    {
        $validator = Validator::make($request->all(), [
            'comment_id' => 'required',
            // 'subject' => 'required',
            'comment' => 'required',
            'file' => 'mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx,txt|max:2048',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            $task = TaskDiscussion::find($request->comment_id);
            $task->company_id = auth()->user()->company_id;
            $task->subject = $request->subject;
            $task->description = $request->comment;
            $task->user_id = auth()->user()->id;
            $task->save();
            if ($request->hasFile('file')) {
                $filePath = $this->uploadImage($request->file, 'uploads/employeeDocuments');
                $task->file_id = $filePath ? $filePath->id : null;
                $task->save();
            }
            return $this->responseWithSuccess('Task Discussion Updated', $task, 200);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), 422);
        }
    }
    public function AppTaskLikeFeedback($request)
    {
        $validator = Validator::make($request->all(), [
            'discussion_id' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            $discussion = DiscussionLike::where('discussion_id', $request->discussion_id)->where('user_id', auth()->user()->id)->first();
            if ($discussion) {
                return $this->responseWithError('You have already liked this discussion', [], 422);
            }
            $task = new DiscussionLike();
            $task->discussion_id = $request->discussion_id;
            $task->user_id = auth()->user()->id;
            if ($request->type == 'like') {
                $task->like = 1;
            } else {
                $task->dislike = 1;
            }
            $task->save();
            return $this->responseWithSuccess('Discussion Reaction Store', $task, 200);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), 422);
        }
    }
    public function GetAppTaskScreen($request)
    {
        $in_progress = 0;
        $completed = 0;
        $due = 0;
        $task_members = TaskMember::with('task')->where('user_id', auth()->user()->id)
            ->get();
        foreach ($task_members as $key => $task_member) {
            if ($task_member->task->status_id == 26) {
                $in_progress++;
            }
            if ($task_member->task->status_id == 27) {
                $completed++;
            }
            if ($task_member->task->end_date < date('Y-m-d')) {
                $due++;
            }
        }
        $staticstics = [
            [
                'count' => $task_members->count(),
                'text' => _trans('tasks.Total Task'),
                'status' => "0",
                'image' => static_asset('public/assets/app/tasks/1.png'),

            ],
            [
                'count' => $in_progress,
                'text' => _trans('tasks.Task in Progress'),
                'status' => "26",
                'image' => static_asset('public/assets/app/tasks/2.png'),
            ],

            [
                'count' => $completed,
                'text' => _trans('tasks.Completed Task'),
                'status' => "27",
                'image' => static_asset('public/assets/app/tasks/3.png'),
            ],

            [
                'count' => $due,
                'text' => _trans('tasks.Due Task'),
                'status' => 'due',
                'image' => static_asset('public/assets/app/tasks/4.png'),
            ],
        ];

        $user_tasks1 = TaskMember::join('tasks', 'tasks.id', 'task_members.task_id')->with('task', 'task.members')->where('user_id', auth()->user()->id)
            ->when(isset(request()->priority), function ($query) {
                return $query->where('tasks.priority', request()->priority);
            })
            ->when(isset(request()->keyword), function ($query) {
                return $query->where('name', 'LIKE', '%' . request()->keyword . '%');
            })->where('tasks.status_id', 26)
            ->take(5)
            ->get();

        $user_tasks2 = TaskMember::join('tasks', 'tasks.id', 'task_members.task_id')->with('task', 'task.members')->where('user_id', auth()->user()->id)
            ->when(isset(request()->priority), function ($query) {
                return $query->where('tasks.priority', request()->priority);
            })
            ->when(isset(request()->keyword), function ($query) {
                return $query->where('name', 'LIKE', '%' . request()->keyword . '%');
            })->where('tasks.status_id', 27)
            ->take(5)
            ->get();

        $tasks_in_collection = $this->TasksCollection($user_tasks1);
        $complete_tasks_collection = $this->TasksCollection($user_tasks2);
 

        $collection = [
            'staticstics' => $staticstics,
            // 'priorities' => $priorities,
            'tasks_in_collection' => $tasks_in_collection,
            'complete_tasks_collection' => $complete_tasks_collection,
        ];

        return $this->responseWithSuccess('App Project Screen', $collection, 200);
    }
    public function AppTaskChangeStatus($request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required',
            'type' => 'required',
            'change_to' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            $task = Task::find($request->task_id);
            if ($request->type == '1') {
                $task->status_id = $request->change_to;
            } else {
                $task->priority = $request->change_to;
            }
            $task->save();
            return $this->responseWithSuccess('Task Status Changed', [], 200);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), 422);
        }
    }
    public function AppTaskDelete($request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            $task = Task::where('id', $request->task_id)->where('created_by', auth()->user()->id)->first();
            if ($task) {
                $task->delete();
                return $this->responseWithSuccess('Task Deleted', [], 200);
            } else {
                return $this->responseWithSuccess('This not your tasks', [], 200);
            }
            return $this->responseWithSuccess('Task Not Found', [], 200);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), 422);
        }
    }
    public function AppTaskList($request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        $tasks = TaskMember::join('tasks', 'tasks.id', 'task_members.task_id')
            ->with('task', 'task.members')
            ->where('user_id', auth()->user()->id)
            ->get();

        //NEED TO REFACTOR, SO DON'T JUDGE ME PLEASE
        if ($request->status == 26) {
            $task_lists = TaskMember::
                join('tasks', 'tasks.id', 'task_members.task_id')
                ->with('task', 'task.members')->where('user_id', auth()->user()->id)
                ->when(isset(request()->priority), function ($query) {
                    return $query->where('tasks.priority', request()->priority);
                })
                ->when(isset(request()->keyword), function ($query) {
                    return $query->where('name', 'LIKE', '%' . request()->keyword . '%');
                })
                ->where('tasks.status_id', 26)
                ->orderBy('tasks.id', 'DESC')
                ->paginate(10);
        } elseif ($request->status == 27) {
            $task_lists = TaskMember::join('tasks', 'tasks.id', 'task_members.task_id')->with('task', 'task.members')->where('user_id', auth()->user()->id)
                ->when(isset(request()->priority), function ($query) {
                    return $query->where('tasks.priority', request()->priority);
                })
                ->when(isset(request()->keyword), function ($query) {
                    return $query->where('name', 'LIKE', '%' . request()->keyword . '%');
                })->where('tasks.status_id', 27)
                ->orderBy('tasks.id', 'DESC')
                ->paginate(10);
        } elseif ($request->status == 'due') {
            $task_lists = TaskMember::join('tasks', 'tasks.id', 'task_members.task_id')->with('task', 'task.members')->where('user_id', auth()->user()->id)
                ->when(isset(request()->priority), function ($query) {
                    return $query->where('tasks.priority', request()->priority);
                })
                ->when(isset(request()->keyword), function ($query) {
                    return $query->where('name', 'LIKE', '%' . request()->keyword . '%');
                })->where('tasks.end_date', '<', date('Y-m-d'))
                ->orderBy('tasks.id', 'DESC')
                ->paginate(10);
        } else {
            $task_lists = TaskMember::join('tasks', 'tasks.id', 'task_members.task_id')->with('task', 'task.members')->where('user_id', auth()->user()->id)
                ->when(isset(request()->priority), function ($query) {
                    return $query->where('tasks.priority', request()->priority);
                })
                ->when(isset(request()->keyword), function ($query) {
                    return $query->where('name', 'LIKE', '%' . request()->keyword . '%');
                })
                ->orderBy('tasks.id', 'DESC')
                ->paginate(10);
        }

        $task_list_collection = $this->TaskListCollectionWithPagination($task_lists);

        $priorities = [
            [
                'title' => _trans('tasks.High'),
                'id' => 30,
                'count' => $tasks->where('priority', 30)->count(),
                'color' => $this->taskPriorityColor(30),
            ],
            [
                'title' => _trans('tasks.Medium'),
                'id' => 31,
                'count' => $tasks->where('priority', 31)->count(),
                'color' => $this->taskPriorityColor(31),
            ],
            [
                'title' => _trans('tasks.Urgent'),
                'id' => 29,
                'count' => $tasks->where('priority', 29)->count(),
                'color' => $this->taskPriorityColor(29),
            ],
        ];

        $collection = [
            'priorities' => $priorities,
            'task_list_collection' => $task_list_collection,
        ];

        return $this->responseWithSuccess('Task Lists', $collection, 200);
    }

    // AppIncomeScreen
    // AppAccountsScreen

    public function GetAppAccountsScreen()
    {

        $staticstics = [
            [
                "title" => _trans('accounts.Income'),
                "amount" => showAmount(89585),
            ],
            [
                "title" => _trans('accounts.Expense'),
                "amount" => showAmount(89585),
            ],
            [
                "title" => _trans('accounts.Receiveable'),
                "amount" => showAmount(89585),
            ],
            [
                "title" => _trans('accounts.Payable'),
                "amount" => showAmount(89585),
            ],
        ];

        $collection = [
            'staticstics' => $staticstics,
        ];

        return $this->responseWithSuccess('App Accounts Screen', $collection, 200);
    }
    public function GetAppIncomeScreen()
    {

        $most_earned_categories = Deposit::select(
            'income_expense_category_id',
            'income_expense_categories.name as item_name',
            DB::raw('SUM(amount) as total_amount')
        )
            ->leftJoin('income_expense_categories', 'income_expense_categories.id', '=', 'deposits.income_expense_category_id')
            ->groupBy('income_expense_category_id')
            ->orderBy('total_amount', 'desc')
            ->where('deposits.company_id', auth()->user()->company_id)
            ->get();

        return $most_earned_categories;
        $most_earned_categories_collection = $most_earned_categories->map(function ($most_earned_category, $index) {
            return [
                'id' => $most_earned_category->id,
                'name' => $most_earned_category->name,
                'amount' => showAmount($most_earned_category->total),
                'image' => static_asset('public/assets/app/clients/' . ++$index . '.png'),
            ];
        });
        return $most_earned_categories_collection;
        $incomeCategory = IncomeExpenseCategory::with('transactions')->where('is_income', 1)->take(4)->get();

        $incomeCategory_collection = $incomeCategory->map(function ($incomeCategory, $index) {
            return [
                'id' => $incomeCategory->id,
                'name' => $incomeCategory->name,
                'amount' => showAmount($incomeCategory->transactions->sum('amount')),
                'image' => static_asset('public/assets/app/clients/' . ++$index . '.png'),
            ];
        });
        $transactions = Transaction::with('incomeExpenseCategory')->where('transaction_type', 19)->take('5')->orderBy('id', 'desc')->get();

        $transaction_collection = $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'name' => @$transaction->incomeExpenseCategory->name,
                'amount' => showAmount($transaction->amount),
                'color' => $transaction->transaction_type == 19 ? '#0xff25A969' : '#0xffF95B51',
                'date' => Carbon::parse($transaction->date)->diffForHumans(),
                'image' => static_asset('public/assets/app/accounts/Income.png'),
            ];
        });

        $collection = [
            'staticstics' => $incomeCategory_collection,
            'transactions' => $transaction_collection,
        ];

        return $this->responseWithSuccess('App Income Screen', $collection, 200);
    }
    public function AppIncomeAdd($request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'date' => 'required',
            'client' => 'required',
            'category' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            $transaction = new Transaction();
            $transaction->amount = $request->amount;
            $transaction->date = $request->date;
            $transaction->client_id = $request->client;
            $transaction->income_expense_category_id = $request->category;
            $transaction->transaction_type = 19;
            $transaction->account_id = 1;
            $transaction->save();

            return $this->responseWithSuccess('App Income Added', [], 200);
        } catch (\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage(), 422);
        }

    }
    public function AppIncomeCreate()
    {
        $clients = Client::get();
        $incomeCategory = IncomeExpenseCategory::where('is_income', 1)->get();

        $client_collection = $clients->map(function ($client) {
            return [
                'id' => $client->id,
                'name' => $client->name,
                'image' => uploaded_asset($client->avatar_id),
            ];
        });

        $catrgory_collection = $incomeCategory->map(function ($incomeCategory) {
            return [
                'id' => $incomeCategory->id,
                'name' => $incomeCategory->name,
            ];
        });

        $date = [
            'clients' => $client_collection,
            'categories' => $catrgory_collection,
        ];

        return $this->responseWithSuccess('App Income Create Screen', $date, 200);
    }
    public function GetAppIncomeList()
    {
        $transactions = Transaction::where('transaction_type', 19)->with('client')->orderBy('id', 'desc')->paginate(10);

        $transaction_collection = $this->TransactionListWithPagination($transactions);

        $collection = [
            'transactions' => $transaction_collection,
        ];

        return $this->responseWithSuccess('App Income Screen', $collection, 200);
    }
    public function TransactionListWithPagination($transactions)
    {
        return [
            'items' => $transactions->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'name' => $transaction->client->name,
                    'amount' => $transaction->amount,
                    'color' => $transaction->transaction_type == 19 ? '#0xff25A969' : '#0xffF95B51',
                    'date' => Carbon::parse($transaction->date)->diffForHumans(),
                    'image' => uploaded_asset($transaction->client->avatar_id),
                ];
            }),
            'pagination' => [
                'total' => $transactions->total(),
                'count' => $transactions->count(),
                'per_page' => $transactions->perPage(),
                'current_page' => $transactions->currentPage(),
                'total_pages' => $transactions->lastPage(),
            ],
        ];
    }
    /* -------------------------------- stck categories -------------------------------- */

    public function BrandCategoryListWithPagination($items)
    {
        return [
            'items' => $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'count' => $item->products->where('status_id', 1)->count() . ' ' . _trans('common.Items'),
                    'avatar' => uploaded_asset($item->avatar_id),
                ];
            }),
            'pagination' => [
                'total' => $items->total(),
                'count' => $items->count(),
                'per_page' => $items->perPage(),
                'current_page' => $items->currentPage(),
                'total_pages' => $items->lastPage(),
            ],
        ];
    }

    public function BrandCategoryList($items)
    {
        return [
            'items' => $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'count' => $item->products->where('status_id', 1)->count() . ' ' . _trans('common.Items'),
                    'avatar' => uploaded_asset($item->avatar_id),
                ];
            }),
        ];
    }

    public function StockProductListWithPagination($items)
    {
        return [
            'items' => $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'brand' => @$item->brand->name,
                    'category' => @$item->category->name,
                    'stock' => $item->total_quantity . ' ' . _trans('common.Items'),
                    'price' => @$item->unit_price,
                    'avatar' => uploaded_asset($item->avatar_id),
                ];
            }),
            'pagination' => [
                'total' => $items->total(),
                'count' => $items->count(),
                'per_page' => $items->perPage(),
                'current_page' => $items->currentPage(),
                'total_pages' => $items->lastPage(),
            ],
        ];
    }
    public function StockProductList($items)
    {
        return [
            'items' => $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'brand' => @$item->brand->name,
                    'category' => @$item->category->name,
                    'stock' => $item->total_quantity . ' ' . _trans('common.Items'),
                    'price' => @$item->unit_price,
                    'avatar' => uploaded_asset($item->avatar_id),
                ];
            }),
        ];
    }

    public function GetAppStockBrands($request)
    {

        if (@$request->keyword !== null) {
            $brands = StockBrand::where('name', 'LIKE', '%' . $request->keyword . '%')->paginate(10);
        } else {
            $brands = StockBrand::paginate(10);
        }
        $collection = $this->BrandCategoryListWithPagination($brands);
        return $this->responseWithSuccess('Brand List', $collection, 200);
    }

    public function GetStockCategories($request)
    {

        if (@$request->keyword !== null) {
            $employees = StockCategory::where('name', 'LIKE', '%' . $request->keyword . '%')->latest()->paginate(10);
        } else {
            $employees = StockCategory::latest()->paginate(10);
        }
        $collection = $this->BrandCategoryListWithPagination($employees);
        return $this->responseWithSuccess('Category List', $collection, 200);
    }

    public function GetAppStockProducts($request)
    {
        try {

            if (@$request->keyword !== null) {
                $items = StockProduct::where('name', 'LIKE', '%' . $request->keyword . '%')->latest()->paginate(10);
            } else {
                $items = StockProduct::paginate(10);
            }
            $collection = $this->StockProductListWithPagination($items);
            return $this->responseWithSuccess('Product List', $collection, 200);
        } catch (\Throwable $th) {
            return $this->responseWithError('Something went wrong', 500);
        }
    }

    // GetAppProjectsStatusList
    public function GetAppProjectsStatusList()
    {
        // '24 = Not Started , 25 = On Hold', '26 = In Progress', '27 = Completed', '28 = Cancelled'
        $statusList = Status::whereIn('id', [24, 25, 26, 27, 28])->get();
        $statusList = $statusList->map(function ($status) {
            return [
                'id' => $status->id,
                'name' => $status->name,
            ];
        });

        $collection = [
            'status' => $statusList,
        ];

        return $this->responseWithSuccess('App Project Status Screen', $collection, 200);
    }

    // start GetUsersList
    public function GetUsersList($type, $id)
    {
        switch ($type) {
            case 'project':
                $AllList = Project::find($id)->members;
                break;
            case 'task':
                $AllList = Task::find($id)->members;
                break; 
            default:
                $AllList = [];
                break;
        }

        $collection = [
            'members' => $this->MemberListCollection($AllList),
        ];
        return $this->responseWithSuccess('App Users Screen', $collection, 200);
    }
}
