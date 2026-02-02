<?php

namespace Bitrio\Wompi\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\Theme\ViewRenderEventManager;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Hook para insertar el botón de Wompi en el checkout
        Event::listen('bagisto.shop.checkout.onepage.summary.wompi.before', 
            static function (ViewRenderEventManager $viewRenderEventManager) {
                $viewRenderEventManager->addTemplate('wompi::checkout.onepage.wompi-widget-button');
            }
        );

        // Listener para guardar transacciones (cuando se genere la factura)
        Event::listen('sales.invoice.save.after', 'Bitrio\Wompi\Listeners\Transaction@saveTransaction');
    }
}
