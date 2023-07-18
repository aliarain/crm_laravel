<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Http\Controllers\Controller;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use ApiReturnFormatTrait;

    protected $profile;


    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profile = $profileRepository;
    }


    //get user profile with parameter
    public function profile(Request $request, $slug): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->profile->getProfile($request, $slug);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    //get details
    public function details(Request $request,$id): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->profile->getProfileDetails($request,$id);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    //profile update
    public function profileUpdate(Request $request, $slug): \Illuminate\Http\JsonResponse
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You are on demo version'), [], 500);
        }
        try {
            return $this->profile->update($request, $slug);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    //password update
    public function passwordUpdate(Request $request): \Illuminate\Http\JsonResponse
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You are on demo version'), [], 500);
        }
        try {
            return $this->profile->changepassword($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    //avatar image update
    public function avatarImageUpdate(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->profile->avatarUpdate($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    //notification
    public function notification(Request $request)
    {
        try {
            return $this->profile->getNotification($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }
    public function readNotification(Request $request)
    {
        try {
            return $this->profile->readNotification($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    public function notificationClear()
    {
        try {
            return $this->profile->clearNotification();
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }
    public function getUserList(Request $request,$keywords=null)
    {
        try {
            $request['keywords']=$keywords;
            return $this->profile->getUserList($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

}
