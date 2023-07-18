<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and

    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function credentials($request)
    {
        if (is_numeric($request->get('email'))) {
            return ['email' => $request->get('email'), 'password' => $request->get('password'), 'is_email' => 0];
        } elseif (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            return ['email' => $request->get('email'), 'password' => $request->get('password'), 'is_email' => 1];
        }
    }


    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required',
                'password' => 'required'
            ], [
                'email.required' => 'Phone or email required'
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $credentials = $this->credentials($request);

            if ($credentials['is_email']) {
                $input = ['email' => $credentials['email'], 'password' => $credentials['password'], 'status_id' => 1];
            } else {
                $input = ['phone' => $credentials['email'], 'password' => $credentials['password'], 'status_id' => 1];
            }

            if (Auth::attempt($input)) {
                // if success login
                if(Auth::user()->status_id == 3){
                    Auth::logout();
                    return response()->json(['error' => ['email' => ["You have been suspended"]]], 422);
                }
                return 0;
            } else {
                return response()->json(['error' => ['email' => ["Credentials doesn't match"]]], 422);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 422);
        }

    }

    public function authenticated()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->back();
        }
    }


}
