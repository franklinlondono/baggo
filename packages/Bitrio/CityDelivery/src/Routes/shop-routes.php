<?php

use Illuminate\Support\Facades\Route;
use Bitrio\CityDelivery\Http\Controllers\Shop\CityDeliveryController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'citydelivery'], function () {
    Route::get('', [CityDeliveryController::class, 'index'])->name('shop.citydelivery.index');

    Route::get('/cities/{stateCode}', [CityDeliveryController::class, 'getCities']);
    Route::post('/update-shipping', [CityDeliveryController::class, 'updateShipping']);
    Route::get('/citydelivery/cities', [CityDeliveryController::class, 'index'])->name('shop.citydelivery.cities');
    Route::post('/citydelivery/rate', [CityDeliveryController::class, 'rate'])->name('shop.citydelivery.rate');
});