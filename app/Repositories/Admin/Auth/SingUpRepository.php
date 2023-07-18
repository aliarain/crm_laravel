<?php

namespace App\Repositories\Admin\Auth;

use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\AuthorInfoTrait;
use App\Helpers\CoreApp\Traits\PermissionTrait;
use App\Models\ActivityLogs\AuthorInfo;
use App\Models\Company\Company;
use App\Models\Hrm\Attendance\Weekend;
use App\Models\Hrm\Shift\Shift;
use App\Models\Role\Role;
use App\Models\Role\RoleUser;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Validator;

class SingUpRepository
{

    use ApiReturnFormatTrait, PermissionTrait;

    protected $company;
    protected $user;

    public function __construct(Company $company, User $user)
    {
        $this->company = $company;
        $this->user = $user;
    }

    public function addPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|max:50|unique:companies,phone',
            'phone' => 'required|max:50|unique:users,phone',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            //save company
            $company = new Company;
            $company->phone = $request->phone;
            $company->status_id = 2;
            $company->save();
            //save session data for caching user
            Session::put('company_id', $company->id);
            Session::put('phone', $company->phone);
            return $this->responseWithSuccess('Phone saved successfully', [], 200);
        } catch (\Exception $exception) {
            return $this->responseWithSuccess($exception->getMessage(), [], 400);
        }
    }

    public function addName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        try {
            //save company
            $company = Company::find(Session::get('company_id'));
            $company->name = $request->name;
            $company->save();
            //save session data for caching user
            Session::put('name', $company->name);
            return $this->responseWithSuccess('Phone saved successfully', [], 200);
        } catch (\Exception $exception) {
            return $this->responseWithSuccess($exception->getMessage(), [], 400);
        }
    }

    public function addEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:100|unique:companies,email',
            'email' => 'required|email|max:100|unique:users,email',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        try {
            //save company
            $company = Company::find(Session::get('company_id'));
            $company->email = $request->email;
            $company->save();
            //save session data for caching user
            Session::put('email', $company->email);
            return $this->responseWithSuccess('Email saved successfully', [], 200);
        } catch (\Exception $exception) {
            return $this->responseWithSuccess($exception->getMessage(), [], 400);
        }
    }

    public function addCompanyName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            //save company
            $company = Company::find(Session::get('company_id'));
            $company->company_name = $request->company_name;
            $company->save();
            //save session data for caching user
            Session::put('company_name', $company->company_name);
            return $this->responseWithSuccess('Company name saved successfully', [], 200);
        } catch (\Exception $exception) {
            return $this->responseWithSuccess($exception->getMessage(), [], 400);
        }
    }

    public function addBusinessType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_type' => 'required|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            //save company
            $company = Company::find(Session::get('company_id'));
            $company->business_type = $request->business_type;
            $company->save();
            //save session data for caching user
            Session::put('business_type', $company->business_type);
            return $this->responseWithSuccess('Business type saved successfully', [], 200);
        } catch (\Exception $exception) {
            return $this->responseWithSuccess($exception->getMessage(), [], 400);
        }
    }

    public function addTradeLicence(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trade_licence_number' => 'required|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            //save company
            $company = Company::find(Session::get('company_id'));
            $company->trade_licence_number = $request->trade_licence_number;
            $company->save();
            //save session data for caching user
            Session::put('trade_licence_number', $company->trade_licence_number);
            return $this->responseWithSuccess('Trade licence number saved successfully', [], 200);
        } catch (\Exception $exception) {
            return $this->responseWithSuccess($exception->getMessage(), [], 400);
        }
    }

    public function addTotalEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total_employee' => 'required|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        try {
            //save company
            $company = Company::find(Session::get('company_id'));
            $company->total_employee = $request->total_employee;
            $company->save();
            //save session data for caching user
            Session::put('total_employee', $company->total_employee);
            return $this->responseWithSuccess('Total employee saved successfully', [], 200);
        } catch (\Exception $exception) {
            return $this->responseWithSuccess($exception->getMessage(), [], 400);
        }
    }

    public function addUserFinally(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'phone' => 'required|max:50|unique:companies,phone,' . Session::get('company_id'),
                'phone' => 'required|max:50|unique:users,phone,' . Session::get('company_id'),
                'email' => 'required|email|max:100|unique:companies,email,' . Session::get('company_id'),
                'email' => 'required|email|max:100|unique:users,email,' . Session::get('company_id'),
                'name' => 'required|max:100',
                'country' => 'required',
                'total_employee' => 'required|max:100',
                'company_name' => 'required|max:100',
                'business_type' => 'required|max:100',
                'trade_licence_number' => 'required|max:100',
                'password' => 'required|required_with:password_confirmation|same:password_confirmation|min:6',
                'password_confirmation' => 'required|min:6'
            ], [
                'country.required' => 'Country field is required'
            ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Required field missing'), $validator->errors(), 422);
        }

        try {
            //update company
            $company = Company::find(Session::get('company_id'));
            $company->country_id = $request->country;
            $company->phone = $request->phone;
            $company->name = $request->name;
            $company->email = $request->email;
            $company->company_name = $request->company_name;
            $company->total_employee = $request->total_employee;
            $company->business_type = $request->business_type;
            $company->trade_licence_number = $request->trade_licence_number;
            $company->status_id = 1;
            $company->save();
            //save user
            $user = new User;
            $user->company_id = $company->id;
            $user->country_id = $company->country_id;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->permissions = $this->adminPermissions();
            $user->save();
            //created by user instance
            $this->createdBy($user);

            //role user
            $role = new Role;
            $role->company_id = $company->id;
            $role->name = 'Admin';
            $role->slug = 'admin';
            $role->permissions = $this->adminRolePermissions();
            $role->status_id = 1;
            $role->save();

            $user->role_id = $role->id;
            $user->save();

            $roleUser = new RoleUser;
            $roleUser->user_id = $user->id;
            $roleUser->role_id = $role->id;
            $roleUser->save();

            $weeklyDays = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
            foreach ($weeklyDays as $day) {
                if ($day == 'friday') {
                    $company->weekends()->create([
                        'name' => $day,
                        'is_weekend' => 'yes',
                        'status_id' => 1,
                    ]);
                } else {
                    $company->weekends()->create([
                        'name' => $day,
                        'is_weekend' => 'no',
                        'status_id' => 1,
                    ]);
                }
            }
            $shifts = ['Day', 'Evening', 'Night'];

            foreach ($shifts as $key => $shift) {
                Shift::create([
                    'company_id' => $company->id,
                    'name' => $shift,
                    'status_id' => 1,
                ]);
            }

            Session::flush();
            return $this->responseWithSuccess('User registered successfully', [], 200);
        } catch (\Exception $exception) {
            return $this->responseWithSuccess($exception->getMessage(), [], 400);
        }
    }

    public function createdBy($model)
    {
        try {
            $author = new AuthorInfo;
            $author->authorable_type = get_class($model);
            $author->authorable_id = $model->id;
            $author->created_by = $model->id;
            $author->save();
            return $author;
        } catch (\Exception $exception) {
            return null;
        }
    }

    public function updatedBy($model)
    {
        try {
            $author = AuthorInfo::where(['authorable_type' => get_class($model), 'authorable_id' => $model->id])->first();
            $author->updated_by = $model->id;
            $author->save();
            return $author;
        } catch (\Exception $exception) {
            return true;
        }
    }
}