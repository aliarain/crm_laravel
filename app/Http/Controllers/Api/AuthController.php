<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|numeric|digits:11',
            'role_id' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required_with:password|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => false, 'message' => 'Input Value must be validate', 'user' => null], 201);
        }

        $checkUser = User::where('phone', $request->phone)->first();
        if ($checkUser != null) {
            return response()->json([
                'result' => false,
                'message' => 'User already exists.',
                'user_id' => 0
            ], 201);
        }

        $user            = new User();
        $user->name      = $request->name;
        $user->phone      = $request->phone;
        $user->current_address      = $request->current_address;
        $user->permanent_address      = $request->permanent_address;
        $user->status      = $request->status;
        $user->password  =  bcrypt($request->password);
        $user->created_by = 1;
        $user->updated_by =  1;
        $user->save();

        $otpController = new OTPVerificationController();
        $otpController->send_code($user);

        $user->assignRole($request->role_id);

        return response()->json([
            'result' => true,
            'message' => 'Registration Successful. Please check your mobile OTP Code',
            'user_id' => $user->id
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:11',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => false, 'message' => 'Input Value must be validate', 'user' => null], 201);
        }

        $user = User::where('phone', $request->phone)->first();

        if ($user != null) {
            if (Hash::check($request->password, $user->password)) {
                $tokenResult = $user->createToken('Personal Access Token');
                return $this->loginSuccess($tokenResult, $user);
            } else {
                return response()->json(['result' => false, 'message' => 'You do not have an account, Please register first', 'user' => null], 201);
            }
        } else {
            return response()->json(['result' => false, 'message' => 'User not found', 'user' => null], 201);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'result' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    protected function loginSuccess($tokenResult, $user)
    {
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(100);
        $token->save();
        return response()->json([
            'result' => true,
            'message' => 'Successfully logged in',
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'avatar' => $user->avatar
            ]
        ]);
    }

    public function ccVerify(Request $request)
    {
        return true;
    }
}
