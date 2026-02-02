<?php

namespace Bitrio\CityCoverageShippingMethod\Carriers;

use Bitrio\CityDelivery\Models\CityDelivery;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Carriers\AbstractShipping;

class CityCoverageShippingMethod extends AbstractShipping
{
    /**
     * Shipping method carrier code.
     */
    protected $code = 'citycoverageshippingmethod';

    /**
     * Shipping method code.
     */
    protected $method = 'citycoverageshippingmethod';

    /**
     * Calculate rate for shipping method.
     *
     * @return \Webkul\Checkout\Models\CartShippingRate|false
     */
    public function calculate()
    {
        if (! $this->isAvailable()) {
            return false;
        }

        $cart = Cart::getCart();
        $address = $cart->shipping_address ?? $cart->billing_address;
        // dd($address);
        if (!$address) {
            return false;
        }
        $cityOrStateName = $address->state;
        // dd($cityOrStateName);
        $price = $this->getConfigData('default_rate') ?? 0;
        if (!empty($cityOrStateName)) {
           
            $coverage = CityDelivery::where(function ($query) use ($cityOrStateName) {
            // Eliminamos puntos para comparar "D.C" con "DC"
            $cleanName = str_replace('.', '', $cityOrStateName);

            $query->whereRaw("REPLACE(name, '.', '') LIKE ?", ["%$cleanName%"])
                ->orWhereRaw("? LIKE CONCAT('%', REPLACE(name, '.', ''), '%')", [$cleanName]);
            })->first();
            // dd($coverage);
            if ($coverage) {
                $price = $coverage->delivery_cost;
            }
            
        }
        $object = new CartShippingRate;

        $object->carrier = 'citycoverageshippingmethod';
        $object->carrier_title = $this->getConfigData('title');
        $object->method = 'citycoverageshippingmethod';
        $object->method_title = $this->getConfigData('title');
        $object->method_description = $this->getConfigData('description');
        $object->price = 0;
        $object->base_price = 0;
    
        $object->price = core()->convertPrice($price);
        $object->base_price = $price;

        return $object;
    }
}