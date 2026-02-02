<?php

namespace Bitrio\Wompi\WompiSdk\Core;

class WompiProductionEnvironment
{
    public function getBaseUrl(): string
    {
        return "https://api.wompi.co/v1";
    }
}