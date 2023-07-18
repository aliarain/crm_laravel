<?php

namespace App\Repositories\Hrm\Visit;

use Validator;
use App\Models\Visit\Visit;
use App\Models\Visit\VisitImage;
use App\Models\Visit\VisitSchedule;
use App\Repositories\Hrm\Visit\VisitRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;


class VisitScheduleRepository
{

    use RelationshipTrait, ApiReturnFormatTrait;

    protected $visit;
    public function __construct(VisitRepository $visit)
    {
        $this->visit = $visit;
    }


    public function createNewSchedule($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'visit_id' => 'required',
                'date' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }

        $schedule = new VisitSchedule;
        $schedule->visit_id = $request->visit_id;
        $schedule->title = 'Visit Rescheduled';
        $schedule->latitude = $request->latitude;
        $schedule->longitude = $request->longitude;
        $schedule->save();

        $visit = $this->visit->getVisitById($request->visit_id);
        $visit->date = $request->date;
        $visit->status = 'created';
        $visit->update();

        return true;
    }
    public function createNewScheduleWithStatus($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'visit_id' => 'required',
                'title' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'date' => 'required',
                // 'status' => 'required|in:created,started,reached,completed,cancelled',
            ]
        );
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }

        $schedule = new VisitSchedule;
        $schedule->visit_id = $request->visit_id;
        $schedule->title = $request->title;
        $schedule->status = $request->status;
        $schedule->latitude = $request->latitude;
        $schedule->longitude = $request->longitude;
        $schedule->note = $request->note;
        if ($request->status =='started') {
            $schedule->started_at = now();
        }
        if ($request->status =='reached') {
            $schedule->reached_at = now();
        }
        $schedule->save();

        $visit = $this->visit->getVisitById($request->visit_id);
        $visit->status = $request->status;
        $visit->update();

        return true;
    }
}
