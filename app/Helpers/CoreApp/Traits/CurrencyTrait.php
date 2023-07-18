<?php

namespace App\Helpers\CoreApp\Traits;

use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Models\coreApp\Setting\CompanyConfig;
use App\Models\Traits\CompanyTrait;

trait CurrencyTrait
{
    use RelationshipTrait;

    protected function getCurrency(): string
    {
        $currency = CompanyConfig::where('company_id', $this->companyInformation()->id)->where('key', 'currency_symbol')->first();
        if ($currency) {
            return $currency->value;
        } else {
            return '$';
        }
    }
}
