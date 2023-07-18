<?php

namespace App\Repositories;

use App\Models\Role\Role;
use App\Models\Hrm\Shift\Shift;
use Illuminate\Support\Facades\Log;
use App\Models\Hrm\Department\Department;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Hrm\Attendance\DutySchedule;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Department\DepartmentRepository;

class DutyScheduleRepository
{
    use RelationshipTrait, ApiReturnFormatTrait;

    protected $dutySchedule;

    public function __construct(DutySchedule $dutySchedule)
    {
        $this->dutySchedule = $dutySchedule;
    }

    public function getShifts()
    {
        return Shift::query()->where('company_id', $this->companyInformation()->id)->get();
    }

    public function index()
    {
        $data['title'] = 'Duty Schedule';
        $data['schedules'] = DutySchedule::query()->where('company_id', $this->companyInformation()->id)->get();
        $data['shifts'] = $this->getShifts();
        return $data;
    }

    public function create()
    {
        $data['title'] = 'Create Duty Schedule';
        $data['shifts'] = Shift::query()->where('company_id', $this->companyInformation()->id)->where('status_id', 1)->get();
        return $data;
    }

    public function show($id)
    {
        $data['title'] = 'Edit Duty Schedule';
        $data['shifts'] = Shift::query()->where('company_id', $this->companyInformation()->id)->where('status_id', 1)->get();
        $data['duty_schedule'] = DutySchedule::find($id);
        return $data;
    }

    public function store($request)
    {
        try {
            foreach ($request->shift_id as $key => $item) {
                if ($this->isExistsWhenStore($this->dutySchedule, 'shift_id', $item)) {
                    $request['company_id'] = $this->companyInformation()->id;

                    if ($request->end_on_same_date == 1) {
                        $end_date = date('Y-m-d');
                    } else {
                        $end_date = date('Y-m-d', strtotime('+1 day'));
                    }
                    $start_time = date('Y-m-d') . ' ' . $request->start_time . ':00';
                    $end_time = $end_date . ' ' . $request->end_time . ':00';
                    $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $start_time);
                    $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $end_time);
                    $diff_in_minutes = $to->diffInMinutes($from);
                    $request['hour'] = $diff_in_minutes / 60;
                    $request['shift_id'] = $item;
                    $this->dutySchedule->create($request->all());
                }
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function update($request)
    {
        try {
            $dutySchedule = $this->dutySchedule->where('id', $request->duty_schedule_id)->first();
            $dutySchedule->shift_id = $request->shift_id;
            $dutySchedule->consider_time = $request->consider_time;
            $dutySchedule->start_time = $request->start_time;
            $dutySchedule->end_time = $request->end_time;
            $dutySchedule->hour = $request->hour;
            $dutySchedule->end_on_same_date = $request->end_on_same_date;
            $dutySchedule->status_id = $request->status_id;
            $dutySchedule->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function distroy($duty_schedule_id)
    {
        try {
            $dutySchedule = $this->dutySchedule->find($duty_schedule_id);
            $dutySchedule->delete();
            return true;
        } catch (\Throwable $th) {
        }
    }


    public function dutyScheduleDataTable($request)
    {
        $dutySchedule = DutySchedule::query()->where('company_id', $this->companyInformation()->id);

        $dutySchedule->when(\request()->get('shift'), function (Builder $builder) {
            return $builder->whereHas('shift', function ($builder) {
                return $builder->where('id', request()->get('shift'));
            });
        });

        $dutySchedule = $dutySchedule->get();

        return datatables()->of($dutySchedule)
            ->addColumn('department', function ($data) {
                return @$data->shift->name;
            })
            ->addColumn('start_time', function ($data) {
                if ($data->start_time) {
                    return showTime($data->start_time);
                }
            })
            ->addColumn('end_time', function ($data) {
                if ($data->end_time) {
                    return showTime($data->end_time);
                }
            })
            ->addColumn('hour', function ($data) {
                if ($data->hour) {
                    return $data->hour;
                }
            })
            ->addColumn('consider_time', function ($data) {
                if ($data->consider_time) {
                    return $data->consider_time;
                }
            })
            ->addColumn('status', function ($data) {
                if ($data->status) {
                    return $data->status;
                }
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->addColumn('action', function ($data) {
                $action_button = '';
                $edit = _trans('common.Edit');
                $delete = _trans('common.Delete');
                if (hasPermission('schedule_update')) {
                    $action_button .= actionButton($edit, route('dutySchedule.show', $data->id), 'profile');
                }
                if (hasPermission('schedule_delete')) {
                    $action_button .= actionButton($delete, '__globalDelete(' . $data->id . ',`hrm/duty/schedule/delete/`)', 'delete');
                }
                $button = '<div class="flex-nowrap">
                    <div class="dropdown">
                        <button class="btn btn-white dropdown-toggle align-text-top action-dot-btn" data-boundary="viewport" data-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">' . $action_button . '</div>
                    </div>
                </div>';
                return $button;
            })
            ->rawColumns(array('department', 'start_time', 'end_time', 'hour', 'consider_time', 'status', 'action'))
            ->make(true);
    }

    public function getUserToDaySchedule()
    {
        $userSchedule = $this->dutySchedule->query()
            ->where('company_id', $this->companyInformation()->id)
            ->where('shift_id', auth()->user()->shift_id)
            ->first();
        if ($userSchedule) {
            $user_duty_schedule = [
                'start_time' => [
                    'hour' => (int) date('H', strtotime($userSchedule->start_time)),
                    'min' => (int) date('i', strtotime($userSchedule->start_time)),
                    'sec' => (int) date('s', strtotime($userSchedule->start_time)),
                ],
                'end_time' => [
                    'hour' => (int) date('H', strtotime($userSchedule->end_time)),
                    'min' => (int) date('i', strtotime($userSchedule->end_time)),
                    'sec' => (int) date('s', strtotime($userSchedule->end_time)),
                ],
            ];
        } else {
            $user_duty_schedule = [
                'start_time' => [
                    'hour' => 0,
                    'min' => 0,
                    'sec' => 0,
                ],
                'end_time' => [
                    'hour' => 0,
                    'min' => 0,
                    'sec' => 0,
                ],
            ];
        }
        return $user_duty_schedule;
    }

    //new functions
    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Shift'),
            _trans('common.Start Time'),
            _trans('common.End Time'),
            _trans('common.Hours'),
            _trans('attendance.Consider Time'),
            _trans('common.Status'),
            _trans('common.Action'),
        ];
    }

    function table($request)
    {

        
        $data = $this->dutySchedule->query()->where('company_id', $this->companyInformation()->id);
        if ($request->from && $request->to) {
            $data = $data->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if ($request->search) {
            $data = $data->whereHas('shift', function ($builder) use ($request) {
                return $builder->where('name', 'like', '%' . $request->search . '%');
            });
        }
        $data = $data->orderBy('id', 'DESC')->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('schedule_update')) {
                    $action_button .= actionButton(_trans('common.Edit'), route('dutySchedule.show', $data->id), 'profile');
                }
                if (hasPermission('schedule_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/duty/schedule/delete/`)', 'delete');
                }
                $button = ' <div class="dropdown dropdown-action">
                             <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                 aria-expanded="false">
                                 <i class="fa-solid fa-ellipsis"></i>
                             </button>
                             <ul class="dropdown-menu dropdown-menu-end">
                             ' . $action_button . '
                             </ul>
                         </div>';
                return [
                    'id'         => $data->id,
                    'shift'       => $data->shift->name,
                    'start_time' => showTime($data->start_time),
                    'end_time' => showTime($data->end_time),
                    'hour' => $data->hour,
                    'consider_time' => $data->consider_time,
                    'status'     => '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>',
                    'action'     => $button
                ];
            }),
            'pagination' => [
                'total' => $data->total(),
                'count' => $data->count(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
                'pagination_html' =>  $data->links('backend.pagination.custom')->toHtml(),
            ],
        ];
    }

    public function destroyAll($request)
    {
        try {
            if (@$request->ids) {
                $duty_schedule = $this->dutySchedule->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                return $this->responseWithSuccess(_trans('message.Duty schedule delete successfully.'), $duty_schedule);
            }else {
                return $this->responseWithError(_trans('message.Duty schedule not found'), [], 400);                
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }

    }
}
