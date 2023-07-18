<?php

namespace App\Http\Controllers\Api\Core\Settings;

use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Http\Controllers\Controller;
use App\Models\Hrm\Designation\Designation;
use App\Repositories\Settings\ProfileUpdateSettingRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileUpdateSettingController extends Controller
{
    use ApiReturnFormatTrait;

    protected ProfileUpdateSettingRepository $profileSetting;
    protected UserRepository $user;

    public function __construct(ProfileUpdateSettingRepository $profileSetting, UserRepository $user)
    {
        $this->profileSetting = $profileSetting;
        $this->user = $user;
    }

    public function getDesignationWiseUsers(Request $request,$designation_id=null)
    {
        try {
            $request['designation_id'] = $designation_id;
            return $this->profileSetting->getDesignationUser($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    public function getDepartment(): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->profileSetting->getAllDepartment();
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }
    public function deleteDepartment(Request $request)
    {
        try {
            $result= $this->profileSetting->deleteDepartment($request);
            
            if($result->original['success']){
                return $this->responseWithSuccess("Department Deleted Successfully", [], 200);
            }else{
                return $this->responseWithError($result->original['message'], [], 500);
            }
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }
    public function storeDepartment(Request $request)
    {
        try {
            return $this->profileSetting->storeDepartment($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }
    public function storeDesignation(Request $request)
    {
        try {
            return $this->profileSetting->storeDesignation($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }
    public function UpdateDepartment(Request $request)
    {
        try {
            return $this->profileSetting->UpdateDepartment($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }
    public function updateDesignation(Request $request)
    {
        try {
            return $this->profileSetting->updateDesignation($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    public function getDesignation(): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->profileSetting->getAllDesignation();
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }
    public function deleteDesignation(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->profileSetting->deleteDesignation($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    public function getEmployment(): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->profileSetting->getEmploymentType();
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    public function getBloodGroup(): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->profileSetting->getBloodGroups();
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    //get All Users
    public function getUsers(Request $request)
    {
        return $this->user->getUserByKeywords($request);
    }


}
