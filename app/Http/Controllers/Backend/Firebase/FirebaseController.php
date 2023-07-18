<?php

namespace App\Http\Controllers\Backend\Firebase;

use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\FirebaseNotification;
use App\Http\Controllers\Controller;
use App\Models\UserDevice\UserDevice;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class FirebaseController extends Controller
{
    use FirebaseNotification, ApiReturnFormatTrait;

    public function firebaseToken(Request $request)
    {
        try {
            $agent = new Agent();
            $device_name = @$request->device_name ?? $agent->device() . '-' . $_SERVER['REMOTE_ADDR'] ;
            $info = UserDevice::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'device_name' => $device_name,
                ],
                [
                    'device_name' => $device_name,
                    'device_token' => $request->firebase_token,
                ]
            );
            return $this->responseWithSuccess('Token Assigned successfully', $info, 200);
        } catch (\Throwable $th) {
            return $this->responseWithError('Something went wrong! try again', [], 400);
        }
    }

    public function initFirebase()
    {
        return response()->view('vendor.notifications.sw_firebase')->header('Content-Type', 'application/javascript');
    }



    function test(){
       return $this->sendFirebaseNotification(59,'leave_request',1,url('/'));
    }
}
