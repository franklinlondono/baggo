<?php

namespace Bitrio\CityDelivery\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;

class CityDeliveryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->app->singleton(\Bitrio\CityDelivery\Repositories\CityDeliveryRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'citydelivery');

         // Carga vistas del paquete
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'citydelivery');
        
    
        View::prependNamespace('shop', __DIR__ . '/../Resources/views');
        View::prependNamespace('velocity', __DIR__ . '/../Resources/views');

        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('citydelivery::admin.layouts.style');
        });
        

    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );
        // $this->mergeConfigFrom(
        //     dirname(__DIR__) . '/Config/menu.php', 'menu'
        // );
    }

    protected function registerShopHooks()
    {
        $this->app['view']->composer('*', function ($view) {
            if (str_contains($view->getName(), 'shop::checkout.onepage')) {
                view()->startPush('scripts');
                echo view('citydelivery::hooks.checkout-address-form')->render();
                view()->stopPush();
            }
        });
    }
}