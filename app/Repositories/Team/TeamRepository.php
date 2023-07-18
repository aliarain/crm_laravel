<?php

namespace App\Repositories\Team;

use App\Models\User;
use App\Models\Hrm\Team\Team;
use App\Models\Company\Company;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Repositories\Interfaces\TeamInterface;
use App\Helpers\CoreApp\Traits\PermissionTrait;

class TeamRepository implements TeamInterface
{
    use FileHandler, PermissionTrait;

    protected Team $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function index()
    {
    }

    public function dataTable($request, $id = null)
    {
        $team = $this->team->query()->where('company_id', auth()->user()->company_id);
        if (@$request->from && @$request->to) {
            $team = $team->whereBetween('created_at', [$request->from, $request->to]);
        }
        if (@$id) {
            $team = $team->where('id', $id);
        }

        return datatables()->of($team->latest()->get())



            ->addColumn('name', function ($data) {
                return @$data->name;
            })

            ->addColumn('team_lead', function ($data) {
                return '<a target="_blank" href="' . url('dashboard/user/show/'.@$data->teamLead->id.'/official') . '">' . @$data->teamLead->name. '</a>';

            })
            ->addColumn('number_of_persons', function ($data) {
                return $data->teams->count();
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->addColumn('action', function ($data) {
                $action_button = '';

                if (hasPermission('team_member_view')) {
                    $action_button .= actionButton('View', '__globalDelete(' . $data->id . ',`dashboard/companies/delete/`)', 'view');
                }
                if (hasPermission('team_update')) {
                    $action_button .= '<a href="' . route('team.show', $data->id) . '" class="dropdown-item"> Edit</a>';
                }
                if (hasPermission('team_delete')) {
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

            ->rawColumns(array('name', 'team_lead', 'number_of_persons', 'status', 'action'))
            ->make(true);
    }
    //teamStore function
    public function teamStore($request)
    {
    

        $team=new $this->team;
        $team->name=$request->name;
        $team->team_lead_id=$request->team_lead_id;
        $team->status_id=$request->status_id;
        $team->user_id=auth()->user()->id;
        $team->company_id=auth()->user()->company_id;
        if ($request->hasFile('file')) {
            $team->attachment_file_id = $this->uploadImage($request->file, 'uploads/employeeDocuments')->id;
        }
        $team->save();

       return  $team;
    }

    public function store($request)
    {
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
        $default_company_configs = CompanyConfig::where('company_id', auth()->user()->company->id)->get();
        foreach ($default_company_configs as $key => $config) {

            $company_config = new CompanyConfig;
            $company_config->key = $config->key;
            $company_config->value = $config->value;
            $company_config->save();

            $company_config->company_id = $crated_company->id;
            $company_config->update();
        }

        //Leave setting clone
        $default_leave_settings = LeaveSetting::where('company_id', auth()->user()->company->id)->first();

        $new_leave_setting = $default_leave_settings->replicate();
        $new_leave_setting->save();

        $new_leave_setting->company_id = $crated_company->id;
        $new_leave_setting->update();



        return $user;
    }

    public function show($id)
    {
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

    public function destroy($id)
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
