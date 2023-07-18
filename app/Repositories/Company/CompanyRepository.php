<?php

namespace App\Repositories\Company;

use App\Models\User;
use App\Models\Role\Role;
use App\Models\Role\RoleUser;
use App\Models\Company\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Hrm\Attendance\Weekend;
use App\Models\Hrm\Leave\LeaveSetting;
use App\Models\ActivityLogs\AuthorInfo;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Models\coreApp\Setting\CompanyConfig;
use App\Repositories\Interfaces\BaseInterface;
use App\Helpers\CoreApp\Traits\AuthorInfoTrait;
use App\Helpers\CoreApp\Traits\PermissionTrait;

class CompanyRepository implements BaseInterface
{
    use FileHandler, PermissionTrait;

    protected Company $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    public function index()
    {

    }

    public function dataTable($request, $id = null)
    {
        $company = $this->company->query();
       
        if (auth()->user()->role_id != 1) {
            $company = $company->where('id', auth()->user()->company_id);
        }else {
            $company = $company->where('id', '!=', auth()->user()->company_id);
        }


        return datatables()->of($company->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                if (auth()->user()->role_id == 1) {
                    if ($data->status_id == 3) {
                            $action_button .= actionButton('Unbanned', 'ApproveOrReject(' . $data->id . ',' . "1" . ',`dashboard/companies/change-status/`,`Approve`)', 'approve');
                    } else {
                            $action_button .= actionButton('Ban', 'ApproveOrReject(' . $data->id . ',' . "3" . ',`dashboard/companies/change-status/`,`Approve`)', 'approve');
                    }
                }
                if (hasPermission('company_update')) {
                    $action_button .= '<a href="' . route('company.show', $data->id) . '" class="dropdown-item"> Edit</a>';
                }
                if (auth()->user()->role_id == 1) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`dashboard/companies/delete/`)', 'delete');
                }
                $button = '<div class="flex-nowrap">
                    <div class="dropdown">
                        <button class="btn btn-white dropdown-toggle align-text-top action-dot-btn" data-boundary="viewport" data-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">' . $action_button . '</div>
                    </div>
                </div>';
                return $button;
            })
            ->addColumn('company_name', function ($data) {
                return @$data->company_name;
            })
            ->addColumn('name', function ($data) {
                return @$data->name;
            })
            ->addColumn('email', function ($data) {
                return @$data->email;
            })
            ->addColumn('phone', function ($data) {
                return @$data->phone;
            })
            ->addColumn('total_employee', function ($data) {
                return @$data->total_employee;
            })
            ->addColumn('business_type', function ($data) {
                return @$data->business_type;
            })
            ->addColumn('trade_licence_number', function ($data) {
                return @$data->trade_licence_number;
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->rawColumns(array('company_name', 'name', 'email', 'phone', 'total_employee', 'business_type', 'trade_licence_number', 'status', 'action'))
            ->make(true);
    }


    public function store($request)
    {
        DB::beginTransaction();
        try {        
                $company = new $this->company();
                $company->name = $request->name;
                $company->company_name = $request->company_name;
                $company->email = $request->email;
                $company->phone = $request->phone;
                $company->total_employee = $request->total_employee;
                $company->business_type = $request->business_type;
                $company->trade_licence_number = $request->trade_licence_number;
                if ($request->hasFile('file')) {
                    $company->trade_licence_id = $this->uploadImage($request->file, 'uploads/employeeDocuments')->id;
                }
                $company->save();

                //save user
                $user = new User;
                $user->company_id = $company->id;
                $user->phone = $request->phone;
                $user->password = Hash::make(12345678);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->permissions = $this->adminPermissions();
                $user->country_id = $request->country_id;
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
                $user->update();

                $roleUser = new RoleUser;
                $roleUser->user_id = $user->id;
                $roleUser->role_id = $role->id;
                $roleUser->save();


                $weekdays = [
                    'saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday',
                ];

                foreach ($weekdays as $day) {
                    $isWeekend = 'no';
                    if ($day == 'friday') {
                        $isWeekend = 'yes';
                    }
                    Weekend::create([
                        'name' => $day,
                        'is_weekend' => $isWeekend,
                        'status_id' => 1,
                        'company_id' => $company->id
                    ]);
                }


                $crated_company = $company;

                //Company Config data clone
                $default_company_configs=CompanyConfig::where('company_id',auth()->user()->company->id)->get();
                foreach ($default_company_configs as $key => $config) {

                    $company_config=new CompanyConfig;
                    $company_config->key=$config->key;
                    $company_config->value=$config->value;
                    $company_config->save();

                    $company_config->company_id=$crated_company->id;
                    $company_config->update();
                }

                //Leave setting clone
                $default_leave_settings=LeaveSetting::where('company_id',auth()->user()->company->id)->first();

                $new_leave_setting=$default_leave_settings->replicate();
                $new_leave_setting->save();

                $new_leave_setting->company_id=$crated_company->id;
                $new_leave_setting->update();
                DB::commit();
                return $user;

        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function show($id)
    {
        if (auth()->user()->role_id==1) {
            return $this->company->query()->find($id);
        } else {
            return $this->company->query()->find(auth()->user()->company->id);
        }


    }

    public function update($request, $id)
    {
        $company = $this->company->query()->find($id);
        $company->name = $request->name;
        $company->company_name = $request->company_name;
        $company->email = $request->email;
        $company->phone = $request->phone;
        $company->total_employee = $request->total_employee;
        $company->business_type = $request->business_type;
        $company->trade_licence_number = $request->trade_licence_number;
        if ($request->hasFile('file')) {
            $company->trade_licence_id = $this->uploadImage($request->file, 'uploads/employeeDocuments')->id;
        }
        $company->save();

        //save user
        $user = $company->user;
        $user->company_id = $company->id;
        $user->phone = $request->phone;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        //created by user instance
        $this->updatedBy($user);
    }

    public function updateCompanyData($request, $id)
    {
        $company = $this->company->query()->find($id);
        $company->name = $request->name;
        $company->company_name = $request->company_name;
        $company->email = $request->email;
        $company->phone = $request->phone;
        $company->total_employee = $request->total_employee;
        $company->business_type = $request->business_type;
        $company->trade_licence_number = $request->trade_licence_number;
        if ($request->hasFile('file')) {
            $company->trade_licence_id = $this->uploadImage($request->file, 'uploads/employeeDocuments')->id;
        }
        $company->save();

    }
    public function destroy( $id)
    {
    }

    public function changeStatus($company, $status)
    {
        $company->user()->update([
            'status_id' => $status
        ]);
        $company->update([
            'status_id' => $status
        ]);
        return true;
    }

    public function getAll()
    {
        return Company::all();
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

    function company(){
        return $this->company->find(auth()->user()->company->id);
    }


}
