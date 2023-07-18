<?php

namespace App\Helpers\CoreApp\Traits;

use GuzzleHttp\Client;
use App\Mail\User\UserCreate;
use Illuminate\Support\Facades\Mail;

trait MailHandler
{

    public function sendEmail($user, $password){
        try {
            Mail::to($user->email)->send(new UserCreate($user, $password));
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }



}
