<?php

namespace Bitrio\Wompi\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class WompiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'wompi');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'wompi');

        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('wompi::admin.layouts.style');
        });
       

        $this->app->bind('payment.wompi', \Bitrio\Wompi\Payment\Wompi::class);
        $this->app->register(EventServiceProvider::class);


    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        // $this->mergeConfigFrom(
        //     dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        // );

        // $this->mergeConfigFrom(
        //     dirname(__DIR__) . '/Config/acl.php', 'acl'
        // );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/paymentmethods.php', 'payment_methods'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );
    }
}