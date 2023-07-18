<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Http\Controllers\Controller;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Mail;
use Validator;

class ForgotPasswordController extends Controller
{

    use ApiReturnFormatTrait;

    protected ProfileRepository $profile;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profile = $profileRepository;
    }

    public function index()
    {
        return view('auth.passwords.forgot-password');
    }

    public function resetPassword()
    {
        if (Session::has('email')) {
            return view('auth.passwords.reset');
        } else {
            return redirect()->route('adminLogin');
        }
    }

    public function sendResetLinkEmail(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->profile->sendEmail($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    public function changePassword(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->profile->updatePassword($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    public function adminChangePassword(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->profile->adminPasswordUpdate($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }
}
