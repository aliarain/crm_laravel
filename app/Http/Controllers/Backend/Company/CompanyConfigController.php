<?php

namespace App\Http\Controllers\Backend\Company;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\coreApp\Setting\DateFormat;
use App\Repositories\HrmLanguageRepository;
use App\Repositories\Settings\ApiSetupRepository;
use App\Repositories\Settings\CompanyConfigRepository;

class CompanyConfigController extends Controller
{
    protected  $config_repo;
    protected $apiSetupRepo;
    protected $hrmLanguageRepo;

    public function __construct(CompanyConfigRepository $companyConfigRepo, ApiSetupRepository $apiSetupRepo, HrmLanguageRepository $hrmLanguageRepo)
    {
        $this->config_repo = $companyConfigRepo;
        $this->apiSetupRepo = $apiSetupRepo;
        $this->hrmLanguageRepo = $hrmLanguageRepo;
    }

    public function index()
    {
        try {
            $data['title']    = _trans('settings.Settings');
            $configs          = $this->config_repo->getConfigs();
            $config_array     = [];
            foreach ($configs as $key => $config) {
                $config_array[$config->key] = $config->value;
            }
            $data['configs']   = $config_array;
            $data['timezones'] = $this->config_repo->time_zone();
            $data['currencies'] = $this->config_repo->currencies();
            $data['hrm_languages'] = $this->hrmLanguageRepo->with('language')->get();
            $data['date_formats'] = DateFormat::get();
            return view('backend.settings.general.company_settings', compact('data'));
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
    // activation
    public function activation()
    {
        try {
            $data['title']    = _trans('settings.Activation');
            $configs          = $this->config_repo->getConfigs();
            $config_array     = [];
            foreach ($configs as $key => $config) {
                $config_array[$config->key] = $config->value;
            }
            $data['configs']   = $config_array;
            $data['timezones'] = $this->config_repo->time_zone();
            $data['currencies'] = $this->config_repo->currencies();
            $data['hrm_languages'] = $this->hrmLanguageRepo->get();
            $data['date_formats'] = DateFormat::get();
            return view('backend.company_setup.activation', compact('data'));
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
    // configuration
    public function configuration()
    {
        try {
            $data['title']    = _trans('settings.Configuration');
            $configs          = $this->config_repo->getConfigs();
            $config_array     = [];
            foreach ($configs as $key => $config) {
                $config_array[$config->key] = $config->value;
            }
            $data['configs']   = $config_array;
            $data['timezones'] = $this->config_repo->time_zone();
            $data['currencies'] = $this->config_repo->currencies();
            $data['hrm_languages'] = $this->hrmLanguageRepo->get();
            $data['date_formats'] = DateFormat::get();
            return view('backend.company_setup.configuration', compact('data'));
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }


    public function update(Request $request)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        try {
            $data = $request->except('_token');
            $result = $this->config_repo->update($data);
            if ($result === true) :
                Toastr::success(_trans('settings.Settings updated successfully'), 'Success');
            else :
                Toastr::error(_trans('response.Something went wrong!'), 'Error');
            endif;
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    //currencyInfo
    public function currencyInfo(Request $request)
    {
        $data = $request->except('_token');
        // echo "<pre>";print_r($this->config_repo->currencyInfo($data));exit;
        return  $this->config_repo->currencyInfo($data);
    }
    public function locationApi()
    {
        $data = [];
        $data['title'] = _trans('settings.API Setup');
        $data['company_apis'] = $this->apiSetupRepo->get();
        return view('backend.settings.general.api_setup', compact('data'));
    }
    public function updateApi(Request $request)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        $data = request()->except('_token');
        $update = $this->apiSetupRepo->update($data);
        if ($update) {
            Toastr::success(_trans('settings.API setup updated successfully'), 'Success');
            return redirect()->back();
        } else {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
}
