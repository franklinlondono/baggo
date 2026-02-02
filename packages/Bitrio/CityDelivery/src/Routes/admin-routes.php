<?php

use Illuminate\Support\Facades\Route;
use Bitrio\CityDelivery\Http\Controllers\Admin\CityDeliveryController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/citydelivery'], function () {
    Route::prefix('city-coverages')->group(function () {
        Route::controller(CityDeliveryController::class)->group(function () {
            Route::get('/', 'index')->name('admin.citydelivery.index');
            Route::get('create', 'create')->name('admin.citydelivery.create');
            Route::post('store', 'store')->name('admin.citydelivery.store');
            Route::get('edit/{id}', 'edit')->name('admin.citydelivery.edit');
            Route::put('update/{id}', 'update')->name('admin.citydelivery.update');
            Route::delete('{id}', 'destroy')->name('admin.citydelivery.destroy');
        });
    });
});