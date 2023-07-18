<?php

namespace App\Repositories\Report;

use Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Models\Hrm\Attendance\EmployeeBreak;
use App\Helpers\CoreApp\Traits\TimeDurationTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class BreakReportRepository
{
    use RelationshipTrait, TimeDurationTrait, ApiReturnFormatTrait, DateHandler;

    protected $model;

    public function __construct(EmployeeBreak $model)
    {
        $this->model = $model;
    }

    public function filter($breakList)
    {
    }

    public function dataTable()
    {
        $breakList = $this->model->query()->with(['user', 'user.department'])->where('company_id', $this->companyInformation()->id)->whereNotNull('back_time');
        if (auth()->user()->role->slug == 'staff') {
            $breakList = $breakList->where('user_id', auth()->id());
        }
        $breakList->when(\request()->get('from_date') && \request()->get('to_date'), function (Builder $builder) use ($breakList) {
            return $breakList->whereBetween('date', [\request()->get('from_date'), \request()->get('to_date')]);
        });
        $breakList->when(\request()->get('user_id'), function (Builder $builder) use ($breakList) {
            return $breakList->where('user_id', \request()->get('user_id'));
        });
        $breakList->when(\request()->get('department'), function (Builder $builder) {
            return $builder->whereHas('user.department', function ($builder) {
                return $builder->where('id', \request()->get('department'));
            });
        });

        return datatables()->of($breakList->get())
            ->addColumn('date', function ($data) {
                return $this->getMonthDate($data->date);
            })
            ->addColumn('name', function ($data) {
                return $data->user->name;
            })
            ->addColumn('department', function ($data) {
                return @$data->user->department->title;
            })
            ->addColumn('break_time', function ($data) {
                return date("g:i a", strtotime("{$data->break_time} UTC"));
            })
            ->addColumn('back_time', function ($data) {
                return date("g:i a", strtotime("{$data->back_time} UTC"));
            })
            ->addColumn('duration', function ($data) {
                return $this->hourOrMinute($data->break_time, $data->back_time);
            })
            ->addColumn('reason', function ($data) {
                return $data->reason;
            })
            ->rawColumns(array('date', 'name', 'department', 'break_time', 'back_time', 'duration', 'reason'))
            ->make(true);
    }

    public function dateSummary($request)
    {

        $validator = Validator::make($request->all(), [
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        $person_data = [];
        $data['date'] = $this->dateFormatWithoutTime($request->date);
        $data['employee_list'] = [];

        $today_breaks = $this->model->query()->where('company_id', $this->companyInformation()->id)->where('date', \request()->get('date'))->whereNotNull('back_time')->groupBy('user_id')->pluck('user_id')->toArray();
        foreach ($today_breaks as $key => $user_id) {
            $user_breaks = $this->model->query()->where('user_id', $user_id)->where('date', \request()->get('date'))
                ->with('user', 'user.designation')
                ->get();
            $totalBreakTime = 0;
            $totalBackTime = 0;
            $totalBreakTimeCount = 0;
            foreach ($user_breaks as $item) {
                $totalBreakTime += $this->timeToSeconds($item->break_time);
                $totalBackTime += $this->timeToSeconds($item->back_time);
            }
            $totalBreakTimeCount = $this->totalSpendTime($totalBreakTime, $totalBackTime);
            $person_data['user_id'] = @$item->user->id;
            $person_data['name'] = @$item->user->name;
            $person_data['designation'] = @$item->user->designation->title;
            $person_data['avatar_id'] = uploaded_asset(@$item->user->avatar_id);
            $person_data['total_break_time'] = $totalBreakTimeCount;

            $data['employee_list'][] = $person_data;
        }
        return $this->responseWithSuccess('Datewise break summary', $data, 200);
    }

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Date'),
            _trans('common.Employee'),
            _trans('common.Department'),
            _trans('common.Start'),
            _trans('common.End'),
            _trans('common.Duration'),
            _trans('common.Reason'),
        ];
    }


    function table($request)
    {

        
        $data = $this->model->query()->with(['user', 'user.department'])->where('company_id', $this->companyInformation()->id)->whereNotNull('back_time');
        if (!is_Admin()) {
            $data = $data->where('user_id', auth()->id());
        }
        if ($request->from && $request->to) {
            $data = $data->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if ($request->search) {
            $data = $data->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->user_id) {
            $data = $data->where('user_id', $request->user_id);
        }
        if ($request->department_id) {
            $data = $data->whereHas('user.department', function ($query) use ($request) {
                $query->where('id', $request->department_id);
            });
        }

        $data = $data->orderBy('id', 'DESC')->paginate($request->limit ?? 2);

        return [
            'data' => $data->map(function ($data) {
                return [
                    'id'         => $data->id,
                    'name'       => $data->user->name,
                    'department' => $data->user->department->title,
                    'date'       => showDate($data->date),
                    'start'      => showTime($data->break_time),
                    'end'        => showTime($data->back_time),
                    'duration'   => $this->hourOrMinute($data->break_time, $data->back_time),
                    'reason'     => $data->reason,
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
}
