<?php

namespace App\Helpers\CoreApp\Traits;

use Illuminate\Support\Facades\Log;
use App\Models\UserDevice\UserDevice;
use App\Models\Notification\NotificationType;
use App\Repositories\Settings\ApiSetupRepository;

trait FirebaseNotification
{
    protected $apiSetupRepo;

    public function __construct(ApiSetupRepository $apiSetupRepo)
    {
        $this->apiSetupRepo = $apiSetupRepo;
    }
    function sendFirebaseNotification($user_id, $notification_type, $id = null, $url)
    {
        try {
            //if env app is not production then return
            if (env('APP_ENV') == 'production' && !env('APP_CRM')) {
                $notification = NotificationType::where('type', $notification_type)->firstOrFail();
                $firebaseToken = UserDevice::where('user_id', $user_id)->whereNotNull('device_token')->pluck('device_token')->all();
                $firebase_key= getSetting('firebase');
                $SERVER_API_KEY= env('SERVER_API_KEY_FIREBASE');
                
                if (isset($firebase_key) && $firebase_key->key!=null) {
                    $SERVER_API_KEY=$firebase_key->key;
                }
                $data = [
                    "registration_ids" => $firebaseToken,
                    
                    "data" => [
                        "title" => $notification->title,
                        "body" => $notification->description,
                        "url" => $url,
                        "id" => $id,
                        "type" => $notification->type,
                        "image" => $notification->icon ? uploaded_asset($notification->icon) : null,
                    ],
                    "aps" => [
                        "title" => $notification->title,
                        "body" => $notification->description,
                        "badge" => "1",
                        "click_action" => $url,
                        "id" => $id,
                        "type" => $notification->type,
                        "sound" => "default",
                        "image" => $notification->icon ? uploaded_asset($notification->icon) : null,
                        "content_available" => true,
                        "priority" => "high",
                    ],
                ];
                $dataString = json_encode($data);

                $headers = [
                    'Authorization: key=' . $SERVER_API_KEY,
                    'Content-Type: application/json',
                ];
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

                $response = curl_exec($ch);
                return $response;
            }else{
               return true;
            }

        } catch (\Throwable $th) {
            return $th;
        }
    }

    function sendCustomFirebaseNotification($user_id, $notification_type, $id = null, $url,$title,$body,$image=null)
    {
        try {
            if (env('APP_ENV') == 'production' && !env('APP_CRM')) {
            $firebaseToken = UserDevice::where('user_id', $user_id)->whereNotNull('device_token')->pluck('device_token')->all();

            $firebase_key= getSetting('firebase');
            $SERVER_API_KEY=env('SERVER_API_KEY_FIREBASE');
            
            if (isset($firebase_key) && $firebase_key->key!=null) {
                $SERVER_API_KEY=$firebase_key->key;
            }
            $data = [
                "registration_ids" => $firebaseToken,
                "data" => [
                    "title" => $title,
                    "body" => $body,
                    "url" => $url,
                    "id" => $id,
                    "type" => $notification_type,
                    "image" => $image,
                ],
                "aps" => [
                    "title" => $title,
                    "body" => $body,
                    "badge" => "1",
                    "click_action" => $url,
                    "id" => $id,
                    "type" => $notification_type,
                    "sound" => "default",
                    "image" => $image,
                    "content_available" => true,
                    "priority" => "high",
                ],
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);
            return $response;
        }else{
            return true;
        }
        } catch (\Throwable $th) {
            return false;
        }
    }
}
