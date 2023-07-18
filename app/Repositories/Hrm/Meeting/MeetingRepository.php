<?php

namespace App\Repositories\Hrm\Meeting;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Hrm\Meeting\Meeting;
use Illuminate\Support\Facades\Log;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Http\Resources\Hrm\MeetingCollection;
use App\Models\Hrm\Meeting\MeetingParticipant;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class MeetingRepository
{
    use ApiReturnFormatTrait, RelationshipTrait, FileHandler;

    protected $model;
    protected $participants;

    public function __construct(Meeting $meeting, MeetingParticipant $meetingParticipant)
    {
        $this->model = $meeting;
        $this->participants = $meetingParticipant;
    }

    public function getModel($filter = null){
        $model = $this->model;
        if ($filter) {
            $model = $this->model->where($filter);
        }
        return $model;
    }

    public function meetingList(): \Illuminate\Http\JsonResponse
    {
        $meeting = $this->model->query()
            ->where(['company_id' => $this->companyInformation()->id, 'user_id' => auth()->id()])
            ->orderByDesc('id')
            ->get();
        $data = new MeetingCollection($meeting);
        return $this->responseWithSuccess('Meeting list', $data, 200);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $meeting = $this->model->query()
            ->with(['meetingParticipants.participant', 'status'])
            ->where(['company_id' => $this->companyInformation()->id, 'user_id' => auth()->id()])
            ->where('id', $id)
            ->first();

        $data = [];
        $data['id'] = $meeting->id;
        $data['title'] = $meeting->title ?? "";
        $data['description'] = $meeting->description ?? "";
        $data['location'] = $meeting->location ?? "";
        $data['meeting_at'] = $meeting->date ?? "";
        $data['duration'] = $meeting->start_at ?? "";
        $data['start_at'] = $meeting->start_at ?? "";
        $data['end_at'] = $meeting->end_at ?? "";
        $data['status'] = @$meeting->status->name ?? "";
        $data['color'] = @$meeting->status->color_code ?? "";
        $data['attachment_file'] = uploaded_asset($meeting->attachment_file_id);
        return $this->responseWithSuccess('Meeting list', $data, 200);
    }

    public function participants($meetingId): \Illuminate\Http\JsonResponse
    {
        $participants = $this->participants->query()->with('participant')
            ->where('meeting_id', $meetingId)
            ->get();
        $data = [];
        foreach ($participants as $key => $participant) {
            $data[$key]['id'] = $participant->id;
            $data[$key]['meeting_id'] = $participant->meeting_id;
            $data[$key]['user_id'] = @$participant->participant->id;
            $data[$key]['name'] = @$participant->participant->name;
        }
        return $this->responseWithSuccess('Participant list', $data, 200);
    }
    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Title'),
            _trans('common.Participants'),
            _trans('common.Date'),
            _trans('common.Time'),
            _trans('common.Action')

        ];
    }
    public function store($request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:191',
            'description' => 'sometimes|max:255',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }


        $meeting = new $this->model;
        $meeting->company_id = $this->companyInformation()->id;
        $meeting->user_id = auth()->id();
        $meeting->title = $request->title;
        $meeting->description = $request->description;
        $meeting->date = $request->date;
        $meeting->location = $request->location;
        $meeting->start_at = $request->start_at;
        $meeting->end_at = $request->end_at;
        if ($request->hasFile('attachment_file')) {
            $meeting->attachment_file_id = $this->uploadImage($request->attachment_file, 'uploads/meeting')->id;
        }
        $meeting->status_id = 1;
        $meeting->save();
        if ($request->participants) {
            $participants = explode(',', $request->participants);
            foreach ($participants as $participant) {
                $meeting->meetingParticipants()->create([
                    'participant_id' => $participant,
                    'is_going' => 0,
                    'is_present' => 0,
                ]);
            }
        }

        return $this->responseWithSuccess('Meeting created successfully', 200);
    }

    public function addParticipants($request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'meeting_id' => 'required',
            'participant_id' => 'required',
            'is_going' => 'required',
            'is_present' => 'required',
            'present_at' => 'required',
            'meeting_started_at' => 'required',
            'meeting_ended_at' => 'required',
            'meeting_duration' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        foreach ($request->participants as $key => $participant) {
            $participants = new $this->participants;
            $participants->meeting_id = $request->meeting_id;
            $participants->participant_id = $participant;
            $participants->is_going = $request->is_going;
            $participants->is_present = $request->is_present;
            $participants->meeting_duration = $request->meeting_duration;
            $participants->meeting_started_at = Carbon::now();
            $participants->meeting_ended_at = Carbon::now();
            $participants->save();
        }
        return $this->responseWithSuccess('Participants added successfully', 200);
    }



    function table($request)
    {
        $files =  $this->model->where(['company_id' => auth()->user()->company_id]);
        if ($request->from && $request->to) {
            $files = $files->whereBetween('date', start_end_datetime($request->from, $request->to));
        }
        if ($request->search) {
            $files = $files->where('title', 'like', '%' . $request->search . '%');
        }
        $files = $files->paginate($request->limit ?? 2);
        return [
            'data' => $files->map(function ($data) {
                $action_button = '';
                if (hasPermission('meeting_view')) {
                    $action_button .= '<a href="' . route('meeting.view', [$data->id]) . '" class="dropdown-item"> <span class="icon mr-8"><i class="fa-solid fa-eye"></i></span>' . _trans('common.View') . '</a>';
                }
                if (hasPermission('meeting_edit')) {
                    $action_button .= '<a href="' . route('meeting.edit', [$data->id]) . '" class="dropdown-item"> <span class="icon mr-8"><i class="fa-solid fa-pen-to-square"></i></span>' . _trans('common.Edit') . '</a>';
                }
                if (hasPermission('meeting_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__deleteAlert(`' . route('meeting.delete', $data->id) . '`)', 'delete');
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
                    'id' => $data->id,
                    'title' => Str::limit($data->title, 20),
                    'date' => showDate($data->date),
                    'time' => @$data->start_at ? @showTime(@$data->start_at?? null) . "-" . @showTime(@$data->end_at ?? null) : null,
                    'participants' => teams($data->meetingParticipants),
                    'created_at' => showDate($data->created_at),
                    'action'   => $button
                ];
            }),
            'pagination' => [
                'total' => $files->total(),
                'count' => $files->count(),
                'per_page' => $files->perPage(),
                'current_page' => $files->currentPage(),
                'total_pages' => $files->lastPage(),
                'pagination_html' =>  $files->links('backend.pagination.custom')->toHtml(),
            ],
        ];
    }

    function createAttributes()
    {
        return [
            'title' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'title',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Title')
            ],

            'location' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'location',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Location')
            ],
            'date' => [
                'field' => 'input',
                'type' => 'date',
                'required' => true,
                'id'    => 'date',
                'class' => 'form-control ot-form-control ot_input s_date',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Date Schedule')
            ],
            'start_time' => [
                'field' => 'input',
                'type' => 'date',
                'required' => true,
                'id'    => 'start_time',
                'class' => 'form-control ot-form-control ot_input s_time',
                'col'   => 'col-md-6 form-group mb-3 mt-3',
                'label' => _trans('common.Start Time')
            ],
            'end_time' => [
                'field' => 'input',
                'type' => 'date',
                'required' => true,
                'id'    => 'start_time',
                'class' => 'form-control ot-form-control ot_input s_time',
                'col'   => 'col-md-6 form-group mb-3 mt-3',
                'label' => _trans('common.End Time')
            ],

            'user_id[]' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'user_id',
                'class' => 'form-select mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Employee'),
                'multiple' => 'multiple',
                'options' => []
            ],
            'description' => [
                'field' => 'textarea',
                'type' => 'textarea',
                'required' => false,
                'row' => 5,
                'id'    => 'description',
                'class' => 'form-control ot_input mt-0',
                'col'   => 'col-md-12 form-group  mb-3',
                'label' => _trans('common.Description')
            ],
            'attachment' => [
                'field' => 'input',
                'type' => 'file',
                'required' => false,
                'id'    => 'attachment',
                'placeholder'=> 'Attachment',
                'class' => 'form-control ot_input ot-form-control mt-0',
                'col'   => 'col-md-12 form-group  mb-3',
                'label' => _trans('common.Attachment')
            ],
            'status_id' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'status_id',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Status'),
                'options' => [
                    [
                        'text' => _trans('payroll.Active'),
                        'value'  => 1,
                        'active' => true,
                    ],
                    [
                        'text' => _trans('payroll.Inactive'),
                        'value'  => 4,
                        'active' => false,
                    ]
                ]
            ]

        ];
    }
    function editAttributes($updateModel)
    {
        
        return [
            'title' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'title',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'value' => @$updateModel->title,
                'label' => _trans('common.Title')
            ],

            'location' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'location',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'value' => @$updateModel->location,
                'label' => _trans('common.Location')
            ],
            'date' => [
                'field' => 'input',
                'type' => 'date',
                'required' => true,
                'id'    => 'date',
                'class' => 'form-control ot-form-control ot_input s_date',
                'col'   => 'col-md-12 form-group mb-3',
                'value' =>date('m/d/y', strtotime(@$updateModel->date)),
                'label' => _trans('common.Date Schedule')
            ],
            'start_time' => [
                'field' => 'input',
                'type' => 'date',
                'required' => true,
                'id'    => 'start_time',
                'class' => 'form-control ot-form-control ot_input s_time',
                'col'   => 'col-md-6 form-group mb-3 mt-3',
                'value' => @$updateModel->start_at,
                'label' => _trans('common.Start Time')
            ],
            'end_time' => [
                'field' => 'input',
                'type' => 'date',
                'required' => true,
                'id'    => 'start_time',
                'class' => 'form-control ot-form-control ot_input s_time',
                'col'   => 'col-md-6 form-group mb-3 mt-3',
                'value' => @$updateModel->end_at,
                'label' => _trans('common.End Time')
            ],

            'user_id[]' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'user_id',
                'class' => 'form-select mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Employee'),
                'multiple' => 'multiple',
                'options' =>  @$updateModel->meetingParticipants->map(function ($data){
                    return [
                        'text' => $data->user->name,
                        'value' => $data->participant_id,
                        'active' => true
                    ];

                })
            ],
            'description' => [
                'field' => 'textarea',
                'type' => 'textarea',
                'required' => false,
                'row' => 5,
                'id'    => 'description',
                'class' => 'form-control ot_input mt-0',
                'col'   => 'col-md-12 form-group  mb-3',
                'value' => @$updateModel->description,
                'label' => _trans('common.Description')
            ],
            'attachment' => [
                'field' => 'input',
                'type' => 'file',
                'required' => false,
                'id'    => 'attachment',
                'class' => 'form-control ot-form-control ot_input mt-0',
                'col'   => 'col-md-12 form-group  mb-3',
                'label' => _trans('common.Attachment')
            ],
            'status_id' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'status_id',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Status'),
                'options' => [
                    [
                        'text' => _trans('payroll.Active'),
                        'value'  => 1,
                        'active' => $updateModel->status_id == 1 ? true : false,
                    ],
                    [
                        'text' => _trans('payroll.Inactive'),
                        'value'  => 4,
                        'active' => $updateModel->status_id == 4 ? true : false,
                    ]
                ]
            ]

        ];
    }

    public function newStore($request)
    {

        try {
            if (@$request->start_time == "00:00:00") {
                return $this->responseWithError('Start Time is required');
            }
            if (@$request->end_time == "00:00:00") {
                return $this->responseWithError('End Time is required');
            }

            $meeting = new $this->model;
            $meeting->company_id = $this->companyInformation()->id;
            $meeting->user_id = auth()->id();
            $meeting->title = $request->title;
            $meeting->description = $request->description;
            $meeting->date = date('Y-m-d' , strtotime($request->date));
            $meeting->location = $request->location;
            $meeting->start_at = $request->start_time;
            $meeting->end_at = $request->end_time;
            if ($request->hasFile('attachment')) {
                $meeting->attachment_file_id = $this->uploadImage($request->attachment, 'uploads/meeting')->id;
            }
            $meeting->status_id = 1;
            $meeting->save();
            if (@$request->user_id) {
                foreach ($request->user_id as $participant) {
                    $meeting->meetingParticipants()->create([
                        'participant_id' => $participant,
                        'is_going' => 0,
                        'is_present' => 0,
                    ]);
                }
            }

            return $this->responseWithSuccess('Meeting created successfully', 200);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), 500);
        }
    }

    public function newUpdate($request, $id)
    {
        DB::beginTransaction();
        try {

            $params                = [
                'id' => $id,
                'company_id' => auth()->user()->company_id,
            ];
            $meeting      = $this->model->where($params)->first();
            if (!$meeting) {
                return $this->responseWithError(_trans('message.Data not found'), 'id', 404);
            }

            if (@$request->start_time == "00:00:00") {
                return $this->responseWithError('response.Start Time is required');
            }
            if (@$request->end_time == "00:00:00") {
                return $this->responseWithError('response.End Time is required');
            }

            $meeting->title = $request->title;
            $meeting->description = $request->description;
            $meeting->date = date('Y-m-d' , strtotime($request->date));
            $meeting->location = $request->location;
            $meeting->start_at = $request->start_time;
            $meeting->end_at = $request->end_time;
            if ($request->hasFile('attachment')) {
                if (@$meeting->attachment_file_id) {
                    $this->deleteImage(asset_path($meeting->attachment_file_id));
                }
                $meeting->attachment_file_id = $this->uploadImage($request->attachment, 'uploads/meeting')->id;
            }
            $meeting->status_id = 1;
            $meeting->save();
            if (@$request->user_id) {
                //delete the old participants
                $meeting->meetingParticipants()->delete();
                foreach ($request->user_id as $participant) {
                    $meeting->meetingParticipants()->create([
                        'participant_id' => $participant,
                        'is_going' => 0,
                        'is_present' => 0,
                    ]);
                }

            }
            DB::commit();

            return $this->responseWithSuccess('Meeting update successfully', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseWithError($th->getMessage(), 500);
        }
    }

    function delete($id)
    {
        $meeting = $this->model->where(['id' => $id, 'company_id' => auth()->user()->company_id])->first();
        if (!$meeting) {
            return $this->responseWithError(_trans('message.Data not found'), 'id', 404);
        }
        try {
            if (@$meeting->attachment_file_id) {
                $this->deleteImage(asset_path($meeting->attachment_file_id));
            }
            $meeting->meetingParticipants()->delete();
            $meeting->delete();
            return $this->responseWithSuccess(_trans('message.Meeting Delete successfully.'), $meeting);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
