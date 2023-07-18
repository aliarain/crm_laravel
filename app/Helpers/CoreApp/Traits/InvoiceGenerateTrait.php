<?php

namespace App\Helpers\CoreApp\Traits;

trait InvoiceGenerateTrait
{
    public function generateCode($model, $prefix): string
    {
        $latest = $model->query()->latest()->first();

        if (!$latest) {
            return "{$prefix}0001";
        }

        $string = preg_replace("/[^0-9\.]/", '', $latest->code); 
        return $prefix . sprintf('%04d', $string + 1);

    }
}