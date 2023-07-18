<?php

namespace App\Repositories\Settings;

use App\Enums\AttendanceMethod;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\coreApp\Setting\IpSetup;
use Illuminate\Support\Facades\Storage;
use App\Models\Hrm\AppSetting\AppScreen;
use App\Repositories\DashboardRepository;
use App\Services\Hrm\EmployeeBreakService;
use App\Repositories\DutyScheduleRepository;
use App\Models\coreApp\Setting\CompanyConfig;
use App\Http\Resources\Hrm\AppScreenCollection;
use App\Repositories\Settings\ApiSetupRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Content\AllContentRepository;
use App\Repositories\Settings\CompanyConfigRepository;

class AppSettingsRepository
{
    use RelationshipTrait, ApiReturnFormatTrait;

    protected $companyConfig;
    protected $appScreen;
    protected $dashboardRepository;
    protected $dutyScheduleRepository;
    protected $allContents;
    protected $thirdPartyApiRepository;

    public function __construct(
        CompanyConfig           $companyConfig,
        AppScreen               $appScreen,
        DashboardRepository     $dashboardRepository,
        CompanyConfigRepository $companyConfigRepo,
        DutyScheduleRepository  $dutyScheduleRepository,
        AllContentRepository    $allContents,
        ApiSetupRepository      $thirdPartyApiRepository
    )
    {
        $this->companyConfig = $companyConfig;
        $this->appScreen = $appScreen;
        $this->dashboardRepository = $dashboardRepository;
        $this->config_repo = $companyConfigRepo;
        $this->dutyScheduleRepository = $dutyScheduleRepository;
        $this->allContents = $allContents;
        $this->thirdPartyApiRepository = $thirdPartyApiRepository;
    }
    public function companyBaseSettings()
    {
        date_default_timezone_set(auth()->user()->country->time_zone);

        $data = [];
        $data['is_admin'] = auth()->user()->is_admin ? true : false;
        $data['is_hr'] = auth()->user()->is_hr ? true : false;
        $data['is_face_registered'] = auth()->user()->face_data ? true : false;
        $data['multi_checkin'] = isset($this->companySetup()['multi_checkin']) ? $this->companySetup()['multi_checkin']== 1 ? true:false: false;
        $data['location_bind'] = isset($this->companySetup()['location_check']) ? $this->companySetup()['location_check']== 1 ? true:false: false;
        $data['is_ip_enabled'] = $this->isIpRestricted();
        $data['default_latitude'] = settings('default_latitude');
        $data['default_longitude'] = settings('default_longitude');
        $data['zoom'] = settings('zoom');

        $data['permissions'] = auth()->user()->permissions;
        $data['time_wish'] = $this->timeWish();
        $data['time_zone'] = auth()->user()->country->time_zone;
        $data['currency_symbol'] = $this->companySetup()['currency_symbol'] ?? '$';
        $data['currency_code'] = $this->companySetup()['currency_code'] ?? 'USD';
        $data['attendance_method'] = $this->companySetup()['attendance_method'] ?? AttendanceMethod::NORMAL;
        $data['duty_schedule'] = $this->dutyScheduleRepository->getUserToDaySchedule();
        $data['fcm_topic'] = settings('fcm_topic');
        $data['location_services'] = [
            'google'=> $this->thirdPartyApiRepository->getConfig('google') ? $this->thirdPartyApiRepository->getConfig('google')->status_id == 1 ? true : false:false,
            'barikoi'=> $this->thirdPartyApiRepository->getConfig('barikoi')?$this->thirdPartyApiRepository->getConfig('barikoi')->status_id == 1 ? true : false:false,
        ];
        $data['google_api_key'] = $this->thirdPartyApiRepository->getConfig('google')->key??null;
        $data['barikoi_api'] = $this->thirdPartyApiRepository->location_api();
        $data['break_status'] = resolve(EmployeeBreakService::class)->isBreakRunning();
        $data['live_tracking'] = ['app_sync_time' => $this->appSyncTime(), 'live_data_store_time' => $this->liveDataStoreTime()];
        $data['location_service'] = $this->locationService();
        return $this->responseWithSuccess('Base settings information', $data, 200);
    }

    public function isIpRestricted(): bool
    {
        $companyId = $this->companyInformation()->id;
        $isIpEnabled = CompanyConfig::where([
            'company_id' => $this->companyInformation()->id,
            'key' => 'ip_check',
            'value' => 1
        ])->first();
        if ($isIpEnabled) {
            return true;
        } else {
            return false;
        }
    }

    public function homeScreenData()
    {
        $report_permission="false";
        if (hasPermission('report') || hasPermission('report_menu')) {
            $report_permission="true";
        }
        $menus = $this->appScreen->query()->where('status_id', 1)->orderBy('position', 'ASC')
            ->select('name','slug','position','icon')
            ->when($report_permission=="false", function ($query) {
                return $query->where('slug', '!=', 'report');
            })
            ->get();
        foreach ($menus as $menu) {
            $image_type = pathinfo($menu->icon,PATHINFO_EXTENSION);
            $menu->image_type = $image_type;
            $menu->icon = my_asset($menu->icon);
        }

        $collection = [
            'data' => $menus
        ];

        // $data = new AppScreenCollection($menus);
        return $this->responseWithSuccess('App home screen menus', $collection, 200);
    }

//    public function newTeamMate()
//    {
//        $menus = $this->appScreen->query()
//            ->where(['company_id' => $this->companyInformation()->id, 'department_id' => auth()->user()->department_id, 'status_id' => 1])
//            ->orderBy('position', 'ASC')
//            ->select('id', 'company_id', 'department_id', 'status_id')
//            ->get();
//
////        return $this->responseWithSuccess('App home screen menus', $data, 200);
//    }

    public function appScreenSetup()
    {
        $data = $this->appScreen->get();

        return $data;
    }

    public function appScreenSetupUpdate($request)
    {
        $data = \App\Models\Hrm\AppSetting\AppScreen::find($request->id);
        if ($request->status == 'true') {
            $data->status_id = 1;
        } else {
            $data->status_id = 4;
        }
        $data->save();

        return true;
    }

    public function timeWish()
    {
        // set default time zone
        date_default_timezone_set(auth()->user()->country->time_zone);

        $current_hour = date('H');
        $time_wish = [];

        if ($current_hour >= 6 && $current_hour < 12) {     // 6 - 11
            $time_wish['wish'] = 'Good Morning';
            $time_wish['sub_title'] = 'Have a good day with full of productivity and good vibes!';
            $time_wish['image'] = asset($this->dashboardRepository->getStatisticsImage('good-morning'));
        } elseif ($current_hour >= 12 && $current_hour < 14) {  //12-2 PM
            $time_wish['wish'] = 'Good Day';
            $time_wish['sub_title'] = 'Best wishes for your day!';
            $time_wish['image'] = asset($this->dashboardRepository->getStatisticsImage('good-day'));
        } elseif ($current_hour >= 12 && $current_hour < 16) {  //2-4 PM
            $time_wish['wish'] = 'Good Afternoon';
            $time_wish['sub_title'] = 'You almost done for today';
            $time_wish['image'] = asset($this->dashboardRepository->getStatisticsImage('good-evening'));
        } elseif ($current_hour >= 16 && $current_hour < 19) {  //4-6 PM
            $time_wish['wish'] = 'Good Evening';
            $time_wish['sub_title'] = 'Thank you for your hard work today';
            $time_wish['image'] = asset($this->dashboardRepository->getStatisticsImage('good-evening'));
        } elseif ($current_hour >= 19) {  //7 pm
            $time_wish['wish'] = 'Good Night';
            $time_wish['sub_title'] = 'Have a good night';
            $time_wish['image'] = asset($this->dashboardRepository->getStatisticsImage('good-night'));
        } elseif ($current_hour < 6) {  //6 AM
            $time_wish['wish'] = 'Good Night';
            $time_wish['sub_title'] = 'Have a good night';
            $time_wish['image'] = asset($this->dashboardRepository->getStatisticsImage('good-night'));
        }
        return $time_wish;

    }

    public function companySetup()
    {
        $configs = $this->config_repo->getConfigs();
        $config_array = [];
        foreach ($configs as $key => $config) {
            $config_array[$config->key] = $config->value;
        }
        $data = $config_array;
        return $data;
    }

    public function getIpAddress()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } //whether ip is from proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } //whether ip is from remote address
        else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        return $ip_address;
    }


    public function allContents($slug)
    {
        $data['contents'] = $this->allContents->getContent($slug);
        return $this->responseWithSuccess('All Contents', $data, 200);
    }


    public function appSyncTime()
    {
        $companyId = $this->companyInformation()->id;
        $data = CompanyConfig::where([
            'company_id' => $this->companyInformation()->id,
            'key' => 'app_sync_time',
        ])->first();

        if ($data) {
            return $data->value;
        } else {
            return '1';
        }
    }

    public function liveDataStoreTime()
    {
        $companyId = $this->companyInformation()->id;
        $data = CompanyConfig::where([
            'company_id' => $this->companyInformation()->id,
            'key' => 'live_data_store_time',
        ])->first();

        if ($data) {
            return $data->value;
        } else {
            return '2';
        }
    }


    public function locationService()
    {
        $companyId = $this->companyInformation()->id;
        $data = CompanyConfig::where([
            'company_id' => $this->companyInformation()->id,
            'key' => 'location_service',
            'value' => 1
        ])->first();

        if ($data) {
            return true;
        } else {
            return false;
        }
    }

    public function getScreenSetup()
    {
        try {
            $data = $this->appScreen->query()->where('status_id', 1)->pluck('slug');
            return $data;
        } catch (\Throwable $th) {
        }
    }

    public function updateTitle($data){
        try {
            $appSetting = $this->appScreen->findOrFail($data->id);
            $appSetting->name = $data->title;
            $appSetting->save();

            Toastr::success(_trans('response.Operation successful'), 'Success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function updateIcon($data){
        try {
            $appSetting = $this->appScreen->findOrFail($data->id);
            // Delete Old Icon
            if(Storage::exists($appSetting->icon)){
                Storage::delete($appSetting->icon);
            }

            // Upload New Icon
            $file = $data->file('icon');
            $originalFileName = $file->getClientOriginalName();
            $fileNameWithoutExtension = pathinfo($originalFileName,PATHINFO_FILENAME);
            $file_name = cleanSpecialCharacters($fileNameWithoutExtension).'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/appSettings/icon/';
            $file->storeAs($destinationPath,$file_name);
            $final_path = $destinationPath.$file_name;

            // Set Icon
            $appSetting->icon = $final_path;

            $appSetting->save();

            Toastr::success(_trans('response.Operation successful'), 'Success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

}
