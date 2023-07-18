<?php

namespace App\Repositories\Settings;

use App\Models\Settings\ApiSetup;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class ApiSetupRepository.
 */
class ApiSetupRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return ApiSetup::class;
    }

    public function update($request)
    {
      try {
        $current_setup=$this->model->query()->where('name',$request['name'])->first();

        if($current_setup){
            if (isset($request['other'])) {
                $other_setup=$this->model->query()->wherein('name',$request['other'])->update(array('status_id' => 4));
            }

            $current_setup->status_id=1;
            $current_setup->key=$request['api_key'];
            $current_setup->secret=@$request['secret'] ?? null;
            $current_setup->endpoint=@$request['api_endpoint'];
            $current_setup->save();
        }else{
            $current_setup=new ApiSetup();
            $current_setup->status_id=1;
            $current_setup->name=$request['name'];
            $current_setup->key=$request['api_key'];
            $current_setup->secret=@$request['secret'] ?? null;
            $current_setup->endpoint=@$request['api_endpoint'];
            $current_setup->company_id=auth()->user()->company_id;

            $current_setup->save();
        }


        return true;
      } catch (\Throwable $th) {
        return false;
      }

    }

    public function location_api()
    {
      try {
        $data=$this->model->query()->where('name','barikoi')
        ->select('key','secret','endpoint','status_id')
        ->first()->toArray();
        return $data;
      } catch (\Throwable $th) {
        return [];
      }
    }

    public function getConfig($name)
    {
       try {
        $data=$this->model->query()->where('name',$name)
        ->select('key','secret','endpoint','status_id')
        ->first();
        return $data;
       } catch (\Throwable $th) {
        return null;
       }
    }
}
