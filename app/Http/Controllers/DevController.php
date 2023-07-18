<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Permission\Permission;
use Illuminate\Support\Facades\Artisan;
use App\Notifications\HrmSystemNotification;
use App\Helpers\CoreApp\Traits\PermissionTrait;

class DevController extends Controller
{
    use PermissionTrait;

    public function sendNotification(Request $request)
    {
        $user = User::first();

        $details = [
            'title' => 'Hi Artisan',
            'body' => 'This is my first notification',
            'actionText' => 'View My Site',
            'actionURL' => [
                'app' => '',
                'web' => url('/'),
                'target' => '_blank',
            ],
            'sender_id' => 46,
        ];

        Notification::send($user, new HrmSystemNotification($details));

    }

    public function permissionUpdate()
    {
        try {
            DB::beginTransaction();
            $delete_existing_permissions = Permission::truncate();
            $attributes = $this->adminRolePermissions();
            foreach ($attributes as $key => $attribute) {
                $permission = new Permission;
                $permission->attribute = $key;
                $permission->keywords = $attribute;
                $permission->save();
            }
            DB::commit();
            Toastr::success(_trans('settings.Permission updated successfully'), 'Success');
            return redirect()->back();
        } catch (\Throwable$th) {
            DB::rollBack();
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }

    }

    public function syncFlug($language_name)
    {
        try {

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($curl);
            curl_close($curl);
        } catch (\Throwable$th) {
        }
    }

    public function initialization()
    {
        putEnvConfigration('APP_ENV', 'local');
        putEnvConfigration('APP_DEBUG', 'false');
        putEnvConfigration('APP_URL', url('/'));

        return redirect()->route('initialization_process');
    }

    public function initializationProcess()
    {
        if (url()->previous() == url('initialize')) {
            $tables = DB::select('SHOW TABLES');
            if (count($tables) > 0) {
                Artisan::call('migrate',
                    array(
                        '--path' => 'database/migrations',
                        '--force' => true,
                    ));
                Artisan::call('optimize:clear');
                Toastr::success(_trans('response.Already initialized - Updated tables.'), 'Success');
                return redirect()->route('adminLogin');
            } else {
                try {
                    Artisan::call('migrate:fresh --seed');
                    putEnvConfigration('APP_ENV', 'production');
                    Artisan::call('storage:link');
                    Artisan::call('optimize:clear');
                    putEnvConfigration('APP_ENV', 'production');
                    // return 'Initialization complete';
                    Toastr::success(_trans('response.Initialization Complete'), 'Success');
                    return redirect()->route('adminLogin');
                } catch (\Throwable$th) {
                    return "Something Went Wrong. Please go to URL: " . env('APP_URL');
                }
            }

            return redirect()->route('initialization_complete');

        } else {
            return redirect()->route('initialization');
        };

    }
}
