<?php

namespace App\Repositories\Interfaces;

interface ProfileInterface
{
    public function getProfile($request,$slug);

    public function avatarUpdate($request);
}
