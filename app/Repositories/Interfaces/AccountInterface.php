<?php

namespace App\Repositories\Interfaces;

interface AccountInterface
{
    public function paymentReject($attributes,$id);
}
