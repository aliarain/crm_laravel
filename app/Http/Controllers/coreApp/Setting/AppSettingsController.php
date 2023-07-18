<?php

namespace App\Http\Controllers\coreApp\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Repositories\Settings\AppSettingsRepository;

class AppSettingsController extends Controller
{
    use ApiReturnFormatTrait;

    protected $appSettings;

    public function __construct(AppSettingsRepository $appSettingsRepository)
    {
        $this->appSettings = $appSettingsRepository;
    }

    public function baseSettings()
    {
        return $this->appSettings->companyBaseSettings();
    }

    public function homeScreen()
    {
        return $this->appSettings->homeScreenData();
    }

    public function newTeamMate()
    {
        return $this->appSettings->newTeamMate();
    }

    public function appScreenSetup(Request $request)
    {
        $data['title'] = _trans('settings.App Screen Setup');
        $data['settings'] = $this->appSettings->appScreenSetup();
        return view('backend.settings.app_setting.index', compact('data'));
    }
    public function appScreenSetupUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        try {
            $this->appSettings->appScreenSetupUpdate($request);
            return response()->json(['message' => $request->name . ' Setup Updated', 'status' => 200]);
        } catch (\Throwable $th) {
            return response()->json(['message' => _trans('response.Something went wrong.'), 'status' => 500]);
        }
    }

    public function getIpAddress()
    {
        try {
            $ip = $this->appSettings->getIpAddress();
            return response()->json($ip);
        } catch (\Throwable $th) {
            return response()->json(['message' => _trans('response.Something went wrong.'), 'status' => 500]);
        }
    }

    public function allContents($slug)
    {
        try {
            return $this->appSettings->allContents($slug);
        } catch (\Throwable $th) {
            return response()->json(['message' => _trans('response.Something went wrong.'), 'status' => 500]);
        }
    }
    public function artisanCommand($command)
    {
        try {
            Artisan::call($command);
        } catch (\Throwable $th) {
        }
    }

    public function updateTitle(Request $request){
        $validator = Validator::make($request->all(),[
            'id'    => 'required|exists:app_screens,id',
            'title' => 'required|max:20'
        ]);

        if($validator->fails()){
            return response()->json(['message'=>$validator->errors()->first(),'status'=>500]);
        }

        return $this->appSettings->updateTitle($request);
    }

    public function updateIcon(Request $request){
        $validator = Validator::make($request->all(),[
            'id'    => 'required|exists:app_screens,id',
            'icon' => 'required|mimes:jpg,jpeg,png,gif,svg',
        ]);

        if($validator->fails()){
            return response()->json(['message'=>$validator->errors()->first(),'status'=>500]);
        }

        return $this->appSettings->updateIcon($request);
    }

}
