<?php

namespace B2B\PriceQtyDisplay\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class PriceQtyDisplayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');


        $this->loadRoutesFrom(__DIR__ . '/../Http/shop-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'priceqtydisplay');

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'priceqtydisplay');

        Event::listen('bagisto.shop.products.price.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('priceqtydisplay::shop.index');
        });

        Event::listen('bagisto.shop.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('priceqtydisplay::shop.layouts.style');
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );
    }
}