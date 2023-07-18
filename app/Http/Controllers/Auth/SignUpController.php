<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Models\Hrm\Country\Country;
use App\Models\Role\RoleUser;
use App\Models\User;
use App\Repositories\Admin\Auth\SingUpRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class SignUpController extends Controller
{
    protected SingUpRepository $signup;

    public function __construct(SingUpRepository $singUpRepository)
    {
        $this->signup = $singUpRepository;
    }

    public function signUp()
    {
        if (Auth::check()) {
            return redirect('dashboard');
        }
        $data['title'] = _trans('auth.Sign up');
        return view('backend.auth.user_register',compact('data'));
    }

    public function getCountry(Request $request)
    {
        return Country::where('name', 'LIKE', "%$request->term%")->take(10)->get();
    }


    public function addPhone(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->signup->addPhone($request);
    }

    public function addName(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->signup->addName($request);
    }

    public function addEmail(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->signup->addEmail($request);
    }

    public function addCompanyName(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->signup->addCompanyName($request);

    }

    public function addBusinessType(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->signup->addBusinessType($request);
    }

    public function addTradeLicence(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->signup->addTradeLicence($request);
    }

    public function addTotalEmployee(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->signup->addTotalEmployee($request);
    }

    public function addUserFinally(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->signup->addUserFinally($request);
    }
}
