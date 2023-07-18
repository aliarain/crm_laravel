<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class AuthController extends Controller
{
    public $token = true;
    protected $profile;

    use ApiReturnFormatTrait;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profile = $profileRepository;
    }

    public function credentials($request)
    {
        if (is_numeric($request->get('email'))) {
            return ['email' => $request->get('email'), 'password' => $request->get('password'), 'is_email' => 0];
        } elseif (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            return ['email' => $request->get('email'), 'password' => $request->get('password'), 'is_email' => 1];
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required',
                'password' => 'required'
            ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('common.Required field missing'), $validator->errors(), 422);

        }

        $input = ['email' => $request->email, 'password' => $request->password];

        $jwt_token = null;
        if (!$jwt_token = JWTAuth::attempt($input)) {
            return $this->responseWithError(_trans('common.Invalid Email or Password'), [], 400);

        }

        $user = User::where('email', $request->email)->first();

        $checkUser['id'] = $user->id;
        $checkUser['company_id'] = $user->company_id;
        $checkUser['is_admin'] = auth()->user()->is_admin ? true : false;
        $checkUser['is_hr'] = auth()->user()->is_hr ? true : false;
        $checkUser['is_face_registered'] = auth()->user()->face_data ? true : false;
        $checkUser['name'] = $user->name;
        $checkUser['email'] = $user->email;
        $checkUser['phone'] = $user->phone;
        $checkUser['avatar'] = uploaded_asset($user->avatar_id);
        $checkUser['token'] = $jwt_token;

        $user->save();

        // exceptional for login
        return $this->responseWithSuccess(_trans('common.Successfully Login'), $checkUser, 200);

    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'result' => true,
                'message' => 'User logged out successfully',
                'error' => ""
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'result' => false,
                'message' => 'Sorry, the user cannot be logged out',
                'error' => ""
            ], 500);
        }
    }

    public function getUser(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        return response()->json(['user' => $user]);
    }


    public function sendResetLinkEmail(Request $request): \Illuminate\Http\JsonResponse
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You are on demo version'), [], 500);
        }
        try {
            return $this->profile->sendEmail($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }

    }

    public function changePassword(Request $request): \Illuminate\Http\JsonResponse
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You are on demo version'), [], 500);
        }
        try {
            return $this->profile->updatePassword($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    public function checkDeviceToken(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->profile->checkDeviceToken($request);
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

}
