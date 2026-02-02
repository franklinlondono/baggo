<?php

namespace Bitrio\CityDelivery\Models;

use Webkul\Core\Models\CountryState as BaseCountryState;
use Webkul\Core\Models\Country;

class CountryState extends BaseCountryState
{
    /**
     * Relación con el país.
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
