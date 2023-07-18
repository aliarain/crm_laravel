<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ProfileRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;

class FaceRecognitionController extends Controller
{
    use ApiReturnFormatTrait;

    protected $profile;


    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profile = $profileRepository;
    }
    //faceRecognition
    public function faceRecognition(Request $request)
    {
        try {
            return $this->profile->faceRecognition($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }
    //getFaceData
    public function getFaceData(Request $request)
    {
        try {
            return $this->profile->getFaceData($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }
}
