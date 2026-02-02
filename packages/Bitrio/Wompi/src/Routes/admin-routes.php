<?php

use Illuminate\Support\Facades\Route;
use Bitrio\Wompi\Http\Controllers\Admin\WompiController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/wompi'], function () {
    Route::controller(WompiController::class)->group(function () {
        Route::get('', 'index')->name('admin.wompi.index');
    });
});