<?php

namespace Bitrio\CityDelivery\Repositories;

use Bitrio\CityDelivery\Models\CityDelivery;
use Webkul\Core\Eloquent\Repository;

class CityDeliveryRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return CityDelivery::class;
    }
}