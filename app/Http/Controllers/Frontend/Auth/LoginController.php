<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class LoginController extends Controller
{
    public function adminLogin()
    {
        $users=[];
        try {
            if (Auth::check()) {
                return redirect('dashboard');
            }
            $branch=Config::get('app.APP_BRANCH');

            if(Schema::hasTable('users')){
                $users =User::whereIn('id', [2,3,4,5])->get();
            }else{
                $users = [];
            }
            return view('backend.auth.admin_login', compact('users'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong'), 'Error');
            return redirect('/');
        }
    }

    public function LoginForm()
    {
        return view('backend.auth.admin_login');
    }
}