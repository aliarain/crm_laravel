<?php

namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\Artisan;

trait ApplicationKeyGenerate
{
    /*
     * We're generating here a new application key for this app as the database is going to be empty.
     */
    protected function keyGenerate()
    {
        Artisan::call('key:generate', array('--force' => true));
    }
}
