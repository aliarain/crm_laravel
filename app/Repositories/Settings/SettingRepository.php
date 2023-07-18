<?php

namespace App\Repositories\Settings;

use App\Models\coreApp\Setting\Setting;

class SettingRepository 
{
    public function getEmailConfigs()
    {
        $data=Setting::select('key','value')->get();
        return $data;
    }

    public function update($request)
    {
        try {
            foreach ($request as $key => $value) {
                $company_config = \App\Models\coreApp\Setting\Setting::firstOrNew(array('name' => $key));
                $company_config->value = $value;
                $company_config->save();
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}