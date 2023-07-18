<?php

namespace App\Http\Controllers\Api\Visit;

use Validator;
use Carbon\Carbon;
use App\Models\Visit\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Http\Resources\Hrm\VisitCollection;
use App\Repositories\Hrm\Visit\VisitRepository;
use App\Http\Resources\Hrm\VisitImageCollection;
use App\Http\Resources\Hrm\VisitHistoryCollection;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Repositories\Hrm\Visit\VisitScheduleRepository;

class VisitController extends Controller
{
    use ApiReturnFormatTrait, DateHandler;

    protected $visit;
    protected $visitSchedule;

    public function __construct(VisitRepository $visit,VisitScheduleRepository $visitSchedule)
    {
        $this->visit = $visit;
        $this->visitSchedule = $visitSchedule;
    }

    public function getVisitList()
    {
        try {
            $visit_list= $this->visit->getList();
            return $this->responseWithSuccess('Visit List Loaded', $visit_list, 200);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }
    public function getVisitHistory(Request $request)
    {
        $visit_history = [];
        try {
            $visit_history= $this->visit->getVisitHistoryList($request);
            if($visit_history == null){
                $visit_history['history']=[];
                return $this->responseWithSuccess('No Visit History Found', $visit_history, 200);
            }else{
                return $this->responseWithSuccess('Visit History Loaded', $visit_history, 200);
            }  
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }


    public function createVisit(Request $request)
    {
         return $this->visit->createNewVisit($request);  
    }
    public function updateVisit(Request $request)
    {
        try {
            return $this->visit->updateVisit($request);     
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    public function getVisitById($id)
    {
        try {

            $visit = $this->visit->visitDetails($id);
            $visit = new VisitCollection($visit);
            return $visit;
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }
    public function uploadImage(Request $request)
    {
        try {
            $image = $this->visit->uploadVisitImage($request);
            $file_path = uploaded_asset($image->file_id);
            return $this->responseWithSuccess('Image Uploaded Successfully', $file_path, 200);
           
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        } 
    }

    public function visitImages($id)
    {
        try {
            $visit = $this->visit->getVisitById($id);
            $visit = new VisitImageCollection($visit);

            return $this->responseWithSuccess('Visit Images Loaded Successfully', [$visit], 200);
          
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    public function removeVisitImage($visit_id,$image_id)
    {
        try {
            $visit = $this->visit->removeVisitImage($visit_id,$image_id);

            return $this->responseWithSuccess('Image Deleted Successfully', [], 200);
           
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    public function createNote(Request $request)
    {
        try {
            $note = $this->visit->createNote($request);
            return $this->responseWithSuccess('Note Created Successfully', [], 200);
           
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    public function createSchedule(Request $request)
    {
        try {
            
            $visit = $this->visitSchedule->createNewSchedule($request);

            return $this->responseWithSuccess('Schedule Created Successfully', [], 200);
           
        } catch (\Exception $exception) {

            return $this->responseWithError($exception->getMessage(), [], 500);
        }

    }

    public function changeVisitStatus(Request $request)
    {
        try {
            return  $this->visit->changeVisitStatus($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(),[], 500);
        }
    }
}
