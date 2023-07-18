<?php

namespace Database\Seeders;

use App\Models\Company\Company;
use App\Models\Settings\HrmLanguage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Hrm\Leave\LeaveSetting;
use App\Models\coreApp\Setting\CompanyConfig;

class CompanyConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config_data = [
            "date_format" => "d-m-Y",
            "time_format" => "h",
            "ip_check" => "0",
            "currency_symbol" => "$",
            "location_service" => "0",
            "app_sync_time" => "",
            "live_data_store_time" => "",
            "lang" => "en",
            "multi_checkin" => 0,
            "currency" => 2,
            "timezone" => "Asia/Dhaka",
            "currency_code" => "USD",
            'location_check' => 0,
            'attendance_method' => 'N',
            'exchange_rate' => 1.00,
        ];
        $companies = Company::get();
        foreach ($companies as $key1 => $company) {
            foreach ($config_data as $key => $value) {
                $company_config = new CompanyConfig;
                $company_config->key = $key;
                $company_config->value = $value;
                $company_config->company_id = $company->id;
                $company_config->save();

                // $company_config->update();


            }

            $leave_setting = LeaveSetting::where('company_id', $company->id)->first();
            if (!$leave_setting) {
                $leave_setting = new LeaveSetting;
                $leave_setting->sandwich_leave = 0;
                $leave_setting->month = 1;
                $leave_setting->prorate_leave = 0;
                $leave_setting->company_id = $company->id;
                $leave_setting->save();

                // $leave_setting->update();

            }
            $apis = [
                'google', 'barikoi'
            ];
            foreach ($apis as $key => $api) {
                DB::table('api_setups')->insert([
                    'name' => $api,
                    'company_id' => $company->id,
                    'status_id' => $key == 0 ? 1 : 4,
                ]);
            }
        }
        HrmLanguage::create([
            'language_id' => 19,
            'is_default' => 1,
            'status_id' => 1,
        ]);
    }
}
