<?php

namespace B2B\PriceRequest\Providers;

use B2B\PriceRequest\Core\Model\Product;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;

class PriceRequestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'pricerequest');

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'pricerequest');

        $this->mergeConfigFrom(dirname(__DIR__) . '/Config/system.php', 'core');

        $this->app->concord->registerModel(
            \Webkul\Product\Contracts\Product::class, Product::class
        );
        
        Event::listen('bagisto.admin.catalog.product.edit.form.Price.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('pricerequest::admin.index');
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
        
        
    }
}