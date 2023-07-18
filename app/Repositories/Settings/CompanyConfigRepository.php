<?php

namespace App\Repositories\Settings;

use App\Models\Settings\Currency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use App\Models\coreApp\Setting\CompanyConfig;

class CompanyConfigRepository
{

    public function getConfigs()
    {
        $data = CompanyConfig::select('key', 'value')->get();
        $data = CompanyConfig::get();
        return $data;
    }


    public function update($request)
    {
        try {
            foreach ($request as $key => $value) {
                if($value || $value == '0'){
                    CompanyConfig::updateOrCreate(
                        [
                            'company_id' => auth()->user()->company_id,
                            'key' => $key
                        ], [
                            'key' => $key,
                            'value' => $value
                        ]
                    );
    
                    if ($key == 'timezone') {
                        $this->setEnvironmentValue('APP_TIMEZONE', $value);
                    }
                    if ($key == 'date_format') {
                        $this->setEnvironmentValue('DATE_FORMAT', $value);
                    }
                }
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }


    public function time_zone()
    {
        return DB::table('time_zones')->where('active_status', 1)->select('id', 'code', 'time_zone')->get();
    }

    public function currencies()
    {
        return DB::table('currencies')->select('id', 'name', 'symbol')->get();
    }

    public function setEnvironmentValue($type, $val)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $val = '"' . trim($val) . '"';
            if (is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0) {
                file_put_contents($path, str_replace(
                    $type . '="' . env($type) . '"',
                    $type . '=' . $val,
                    file_get_contents($path)
                ));
            } else {
                file_put_contents($path, file_get_contents($path) . "\r\n" . $type . '=' . $val);
            }
        }
        Artisan::call('optimize:clear');
        return true;
    }

    public function currencyInfo($request)
    {
        try {
            $currency = Currency::where('id', $request['currency_id'])->select('id', 'name', 'symbol', 'code','exchange_rate')->first();
            return response()->json([
                'success' => true,
                'status' => 'success',
                'currency' => $currency
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Something went wrong!'
            ]);
        }
    }

    public static function setupConfig($key, $value)
    {
        $check_exist = CompanyConfig::where('key', $key)->first();
        if ($check_exist == '') {
            $check_exist = new CompanyConfig;
        }
        $check_exist->key = $key;
        $check_exist->value = $value;
        $check_exist->save();
        return true;

    }


}
