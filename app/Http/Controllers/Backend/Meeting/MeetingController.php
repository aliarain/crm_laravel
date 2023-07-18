<?php

namespace App\Http\Controllers\Backend\Meeting;

use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Http\Controllers\Controller;
use App\Repositories\Hrm\Meeting\MeetingRepository;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    use ApiReturnFormatTrait;

    protected $meeting;

    public function __construct(MeetingRepository $meetingRepository)
    {
        $this->meeting = $meetingRepository;
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->meeting->store($request);
        } catch (\Throwable $exception) {
            return $this->responseWithError($exception->getMessage(), 400);
        }
    }

    public function meetingList(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->meeting->meetingList($request);
        } catch (\Throwable $exception) {
            return $this->responseWithError($exception->getMessage(), 400);
        }
    }
    public function show($id): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->meeting->show($id);
        } catch (\Throwable $exception) {
            return $this->responseWithError($exception->getMessage(), 400);
        }
    }

    public function addParticipants(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->meeting->addParticipants($request);
        } catch (\Throwable $exception) {
            return $this->responseWithError(_trans('response.Something went wrong.'), 400);
        }
    }

    public function participants($meetingId)
    {
        try {
            return $this->meeting->participants($meetingId);
        } catch (\Throwable $exception) {
            return $this->responseWithError(_trans('response.Something went wrong.'), 400);
        }
    }
}
