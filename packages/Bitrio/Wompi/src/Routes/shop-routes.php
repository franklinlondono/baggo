<?php

use Illuminate\Support\Facades\Route;
use Bitrio\Wompi\Http\Controllers\Shop\WompiController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'wompi'], function () {
    Route::get('', [WompiController::class, 'index'])->name('shop.wompi.index');
    // Ruta para redirección de pago
    Route::get('/redirect', [WompiController::class, 'redirect'])->name('wompi.redirect');
    // Callback de Wompi (cuando Wompi devuelve la respuesta del pago)
    Route::get('/callback', [WompiController::class, 'callback'])->name('wompi.callback');

    Route::get('/create-order', [WompiController::class, 'createWompiOrder'])->name('wompi.create-order');
});