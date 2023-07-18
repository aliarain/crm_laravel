<?php

namespace App\Http\Controllers\coreApp\Setting;

use function view;
use App\Models\User;
use function config;
use function request;
use function session;
use function redirect;
use function __translate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use App\Models\Permission\Permission;
use App\Models\coreApp\Setting\Setting;
use App\Models\Database\DatabaseBackup;
use Illuminate\Support\Facades\Artisan;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Helpers\CoreApp\Traits\PermissionTrait;
use App\Repositories\Settings\SettingRepository;
use App\Repositories\Hrm\Leave\LeaveSettingRepository;
use App\Repositories\Settings\CompanyConfigRepository;

class SettingsController extends Controller
{

    use FileHandler, PermissionTrait;

    protected LeaveSettingRepository $leaveSetting;
    protected $settingRepo;
    protected $companyConfigRepo;

    public function __construct(LeaveSettingRepository $leaveSettingRepository, SettingRepository $settingRepo, CompanyConfigRepository $companyConfigRepo)
    {
        $this->leaveSetting = $leaveSettingRepository;
        $this->settingRepo = $settingRepo;
        $this->companyConfigRepo = $companyConfigRepo;
    }

    public function index()
    {
        try {
            $data['title'] = _trans('settings.Settings');
            $data['databases'] = DatabaseBackup::orderByDesc('id')->get();
            return view('backend.settings.general.settings', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
    public function newIndex()
    {
        try {
            $data['title'] = _trans('settings.Settings');
            $data['databases'] = DatabaseBackup::orderByDesc('id')->get();
            $data['settings']  = DB::table('settings')->where('company_id', auth()->user()->company_id)->get();
            return view('backend.settings.general.general_settings', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
    public function testEmailSetup(Request $request){
        try {
            $info = array(
                'name' => env('APP_NAME')
            );

            Mail::send('emails.test_email', $info, function ($message) use($request)
            {
                $message->to($request->receiver_email, 'Test Email Receiver')
                    ->subject('Basic test eMail from ' . env('APP_NAME'));
                $message->from(env('MAIL_USERNAME'), env('APP_NAME'));
            });
            try {
                setSystemSettingData('email_setup',1);
            } catch (\Throwable $th) {
            }
             Toastr::success(_trans('response.Test Email Send Successfully'), 'Success');
            return redirect()->back();
        }catch(\Exception $e){
            setSystemSettingData('email_setup',0);
            Toastr::error('Your email configuration is wrong!', 'Error');
            return redirect()->back();
        }
    }

    public function leaveSettings()
    {
        try {
            $data['title'] = _trans('leave.Leave');
            $data['leaveSetting'] = $this->leaveSetting->getLeaveSetting();
            return view('backend.settings.leave_settings.index', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function leaveSettingsEdit()
    {
        try {
            $data['title'] = _trans('leave.Leave');
            $data['leaveSetting'] = $this->leaveSetting->getLeaveSetting();
            return view('backend.settings.leave_settings.edit', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function emailSetup(Request $request)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        $request = $request->except('_token');
        try {
            foreach ($request as $key => $value) {
                $company_config = \App\Models\coreApp\Setting\Setting::firstOrNew(array('name' => $key));
                $company_config->value = $value;
                $company_config->save();

                putEnvConfigration($key, $value);
            }
            try {
                setSystemSettingData('email_setup',0);
            } catch (\Throwable $th) {
            }
            Toastr::success(_trans('settings.Email settings updated successfully'), 'Success');
            return redirect('/admin/settings/?email_setup=true');
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function storageSetup(Request $request)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        $request = $request->except('_token');
        try {
            foreach ($request as $key => $value) {
                $company_config = \App\Models\coreApp\Setting\Setting::firstOrNew(array('name' => $key));
                $company_config->value = $value;
                $company_config->save();

                putEnvConfigration($key, $value);
            }
            Toastr::success(_trans('settings.Storage settings updated successfully'), 'Success');
            return redirect('/admin/settings/?storage_setup=true');
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function leaveSettingsUpdate(Request $request)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        try {
            $this->leaveSetting->settingUpdate($request);
            Toastr::success(_trans('settings.Settings updated successfully'), 'Success');
            return redirect()->route('leaveSettings.view');
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_description' => 'nullable|max:255',
            'company_name' => 'nullable|max:150',
            'android_url' => 'nullable|max:255',
            'android_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ios_url' => 'nullable|max:255',
            'ios_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'company_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'white_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'dark_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'backend_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        try {
            $settings = request()->except('_token');
            $i = 0;
            if (isset($settings['company_name'])) {
                putEnvConfigration('APP_NAME', $settings['company_name']);
            }
            foreach ($settings as $key => $item) {
                $new_setup = DB::table('settings')->where('name', $key)->first();
                if (!blank($new_setup)) {
                   $new_setup = DB::table('settings')->where('name', $key)
                        ->update(['value' => $item]);
                   
                }else {
                    $new_setup = new Setting;
                    $new_setup->name = $key;
                    $new_setup->value = $item;
                    $new_setup->save();
                }
                //upgrade base app settings
                config()->set("settings.app.{$key}", $item);
                //change language
                if ($key == 'language') {
                    App::setLocale($item);
                    session()->put('locale', $item);
                }
                if (request()->file($key)) {
                    $settings[$key] = $this->uploadImage(request()->file($key), 'uploads/settings/logo');
                    DB::table('settings')->where('name', $key)->update([
                        'value' => $settings[$key]->id
                    ]);
                }
                $i++;
            }
            Toastr::success(_trans('settings.Settings updated successfully'), 'Success');
            return redirect('/admin/settings/?general_setting=true');
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
    public function permissionUpdate()
    {
        DB::beginTransaction();
        try {
            $delete_existing_permissions = Permission::query()->delete();
            $attributes = $this->adminRolePermissions();
            $user_permission_array = [];
            foreach ($attributes as $key => $attribute) {
                $permission = new Permission;
                $permission->attribute = $key;
                $permission->keywords = $attribute;
                $permission->save();
                foreach ($attribute as $key => $value) {
                    $user_permission_array[] = $value;
                }
            }
            $admin_permission = User::find(auth()->user()->id);
            $admin_permission->permissions = $user_permission_array;
            $admin_permission->save();
            DB::commit();
            Toastr::success(_trans('settings.Permission updated successfully'), 'Success');
            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
}
