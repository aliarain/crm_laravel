<?php

namespace App\Repositories\Settings;

use Validator;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Services\Hrm\DeleteService;
use App\Models\Hrm\Department\Department;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Hrm\UserCollection;
use App\Models\Hrm\Designation\Designation;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class ProfileUpdateSettingRepository
{
    use ApiReturnFormatTrait, RelationshipTrait;

    protected User $user;
    protected Department $department;
    protected Designation $designation;

    public function __construct(User $user, Department $department, Designation $designation)
    {
        $this->user = $user;
        $this->department = $department;
        $this->designation = $designation;
    }


    public function getDesignationUser($request)
    {
        $designation_id= $request->get('designation_id');
        $designation = $this->designation->query()->where('id', $designation_id)->first();
        $users = $this->user->query()->where('company_id', auth()->user()->company_id);
        $users->when(\request()->get('designation_id'),function(Builder $builder){
                return $builder->where('designation_id',\request()->get('designation_id'));
        });
        $users->when(\request()->get('department_id'),function(Builder $builder){
            return $builder->where('department_id',\request()->get('department_id'));
         });
         $users->select('id', 'name', 'phone', 'designation_id', 'avatar_id');

        $data = $users->when(\request('keywords'), function (Builder $builder) {
            $keywords = \request('keywords');
            return $builder->where('name', 'LIKE', "%$keywords%");
        });
        $array = $users->take(20)->get();
        $data = new UserCollection($array);

        return $this->responseWithSuccess("Users", $data, 200);
    }

    public function getAllDepartment(): \Illuminate\Http\JsonResponse
    {
        $data['departments'] = $this->department->query()->select('id', 'title','status_id','created_at')->where(['company_id' => $this->companyInformation()->id, 'status_id' => 1])->get();
        $data['departments']=$data['departments']->map(function ($department){
            return [
                'id'=>$department->id,
                'title'=>$department->title,
                'status'=>$department->status->name,
                'created_at'=>Carbon::parse($department->created_at)->format('d M Y'),
            ];
        });
        return $this->responseWithSuccess('All Department', $data, 200);
    }
    function deleteDepartment($request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        try {
          
            $department = $this->department->query()->where('id', $request->id)->first();
           
            $delete= DeleteService::deleteDataApi('departments', 'department_id', $department->id);
            return $delete;
            if($delete){
                return $this->responseWithSuccess('Department Deleted Successfully', [], 200);
            }
            return $this->responseWithError('Department Not Deleted', $department, 200);
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage(), 500);
        } 

    }
    function deleteDesignation($request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError($validator->errors()->first(), 422);
        }
        try {
          
            $designation = $this->designation->query()->where('id', $request->id)->first();
           
            $delete= DeleteService::deleteDataApi('designations', 'designation_id', $designation->id);
            if($delete->original['success']){
                return $this->responseWithSuccess('Designation Deleted Successfully', [], 200);
            }else{
                return $this->responseWithError($delete->original['message'], [], 500);
            }
            return $this->responseWithError('Designation Not Deleted', $designation, 200);
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage(), 500);
        } 

       
    }
    function storeDepartment($request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:departments,title',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError($validator->errors()->first(), 422);
        }

        try {
            DB::beginTransaction();
            $this->department->query()->create([
                'title' => $request->title,
                'company_id' => $this->companyInformation()->id,
                'status_id' => 1,
            ]);
            DB::commit();
            return $this->responseWithSuccess('Department Created Successfully', [], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseWithError($e->getMessage(), 500);
        } 

    }
    function storeDesignation($request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:designations,title',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError($validator->errors()->first(), 422);
        }

        try {
            DB::beginTransaction();
            $this->designation->query()->create([
                'title' => $request->title,
                'company_id' => $this->companyInformation()->id,
                'status_id' => 1,
            ]);
            DB::commit();
            return $this->responseWithSuccess('Designation Created Successfully', [], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseWithError($e->getMessage(), 500);
        } 

    }
    function updateDesignation($request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'title' => 'required|unique:designations,title,'.$request->id,
        ]);
        if ($validator->fails()) {
            return $this->responseWithError($validator->errors()->first(), 422);
        }

        try {
            DB::beginTransaction();
           
            $this->designation->query()->where('id',$request->id)->update([
                'title' => $request->title,
            ]);
            DB::commit();
            return $this->responseWithSuccess('Designation Updated Successfully', [], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseWithError($e->getMessage(), 500);
        } 

    }
    function UpdateDepartment($request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'title' => 'required|unique:departments,title,'.$request->id,
        ]);
        if ($validator->fails()) {
            return $this->responseWithError($validator->errors()->first(), 422);
        }

        try {
            DB::beginTransaction();
           
            $this->department->query()->where('id',$request->id)->update([
                'title' => $request->title,
            ]);
            DB::commit();
            return $this->responseWithSuccess('Department Updated Successfully', [], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseWithError($e->getMessage(), 500);
        } 

    }

    public function getAllDesignation(): \Illuminate\Http\JsonResponse
    {
        $data['designations'] = $this->designation->query()->where('status_id', 1)->get();
        $data['designations']=$data['designations']->map(function ($designation){
            return [
                'id'=>$designation->id,
                'title'=>$designation->title,
                'status'=>$designation->status->name,
                'created_at'=>Carbon::parse($designation->created_at)->format('d M Y'),
            ];
        });
        return $this->responseWithSuccess('All Designation', $data, 200);
    }

    public function getEmploymentType(): \Illuminate\Http\JsonResponse
    {
        $data['types'] = config('hrm.employee_type');
        return $this->responseWithSuccess('All employment type', $data, 200);
    }

    public function getBloodGroups(): \Illuminate\Http\JsonResponse
    {
        $data['blood_group'] = config('hrm.blood_group');
        return $this->responseWithSuccess('All employment type', $data, 200);
    }

    public function getDepartmentWiseUsers($request)
    {
        $designation = $this->designation->query()->where('id', $designation_id)->first();
        $users = $this->user->query()->where('company_id', auth()->user()->company_id);
        $users->when(\request()->get('designation_id'),function(Builder $builder){
                return $builder->where('designation_id',\request()->get('designation_id'));
        });
        $users->when(\request()->get('department_id'),function(Builder $builder){
            return $builder->where('department_id',\request()->get('department_id'));
         });
         $users->select('id', 'name', 'phone', 'designation_id', 'avatar_id');

        $data = $users->when(\request('keywords'), function (Builder $builder) {
            $keywords = \request('keywords');
            return $builder->where('name', 'LIKE', "%$keywords%");
        });
        $array = $users->take(20)->get();
        $data = new UserCollection($array);

        return $this->responseWithSuccess("Users", $data, 200);
    }
}
