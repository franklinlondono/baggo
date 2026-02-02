<?php

namespace Bitrio\CityDelivery\Http\Controllers\Shop;

use Webkul\Shop\Http\Controllers\Controller;
use Bitrio\CityDelivery\Models\CityDelivery;

class CityDeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = CityDelivery::active()
        ->with(['state.country'])
        ->get();

        $data = $cities->map(function ($city) {
            return [
                'id'            => $city->id,
                'name'          => $city->name,
                'municipal_code'  => $city->municipal_code,
                'delivery_cost' => $city->delivery_cost,
                'state'         => $city->state->default_name ?? null,
                'country'       => $city->state->country->name ?? null,
            ];
        });
        
        return response()->json($data);
       
        
    }

    public function getCities($stateCode)
    {
        $cities = CityDelivery::query()
            ->active()
            ->whereHas('state', fn($q) => $q->where('code', $stateCode))
            ->where('is_active', true)
            ->get(['id', 'name', 'delivery_cost']);

        return response()->json($cities);
    }

}
