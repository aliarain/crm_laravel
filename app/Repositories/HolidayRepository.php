<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Models\Hrm\Attendance\Holiday;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class HolidayRepository
{
    use FileHandler, RelationshipTrait, ApiReturnFormatTrait;

    protected Holiday $holiday;

    public function __construct(Holiday $holiday)
    {
        $this->holiday = $holiday;
    }

    public function index()
    {
        return $this->holiday->query()->with('status')->where('company_id', $this->companyInformation()->id)->get();
    }

    public function appScreen()
    {
        return $this->holiday->query()
            ->when(request()->has('month'), function ($query) {
                $query->where('start_date', 'LIKE', '%' . request('month') . '%');
            })
            ->when(!request()->has('month'), function ($query) {
                $query->where('start_date', '>=', date('Y-m-d'))->limit(5);
            })
            ->orderBy('start_date', 'ASC')
            ->get();
    }

    public function store($request)
    {
        if ($request->file) {
            $request['attachment_id'] = $this->uploadImage($request->file)->id;
        }
        $this->holiday->query()->create($request->all());
        return true;
    }

    public function update($request)
    {
        $holiday = $this->holiday->where('id', $request->holiday_id)->first();
        $holiday->title = $request->title;
        $holiday->description = $request->description;
        $holiday->start_date = $request->start_date;
        $holiday->end_date = $request->end_date;
        $holiday->status_id = $request->status_id;
        if ($request->file) {
            $request['attachment_id'] = $this->uploadImage($request->file)->id;
            $holiday->attachment_id = $request->attachment_id;
        }
        $holiday->save();
        return true;
    }

    public function delete($model): bool
    {
        $model->delete();
        return true;
    }


    // new functions
    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Title'),
            _trans('common.Description'),
            _trans('common.File'),
            _trans('common.Start date'),
            _trans('common.End date'),
            _trans('common.Status'),
            _trans('common.Action'),
        ];
    }

    function table($request)
    {

        
        $data = $this->holiday->query()->where('company_id', $this->companyInformation()->id);
        if ($request->from && $request->to) {
            $data = $data->whereBetween('start_date', start_end_datetime($request->from, $request->to));
            $data = $data->orWhereBetween('end_date', start_end_datetime($request->from, $request->to));
        }
        if ($request->search) {
            $data = $data->where('name', 'like', '%' . $request->search . '%');
        }
        $data = $data->orderBy('id', 'DESC')->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('holiday_read')) {
                    $action_button .= '<a href="' . route('holidaySetup.show', $data->id) . '" class="dropdown-item"> <span class="icon mr-8"><i class="fa-solid fa-pen-to-square"></i></span>' . _trans('common.Edit') . '</a>';
                }
                if (hasPermission('holiday_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/holiday/setup/delete/`)', 'delete');
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
                    'title'       => ucfirst($data->title),
                    'description' => Str::limit($data->description, 20),
                    'file'       => $data->attachment_id ? '<a href="' . uploaded_asset($data->attachment_id) . '" download class="btn btn-white btn-sm"><i class="fas fa-download"></i></a>' : _trans('common.No File'),
                    'start_date' => showDate($data->start_date),
                    'end_date' => showDate($data->end_date),
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
                $holidays = $this->holiday->where('company_id', auth()->user()->company_id)->whereIn('id', $request->ids)->delete();
                return $this->responseWithSuccess(_trans('message.Holiday delete successfully.'), $holidays);
            }else {
                return $this->responseWithError(_trans('message.Holiday not found'), [], 400);                
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }

    }
}
