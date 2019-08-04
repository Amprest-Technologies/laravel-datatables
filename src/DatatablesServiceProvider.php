<?php

namespace Amprest\LaravelDatatables;

use Illuminate\Support\ServiceProvider;

class DatatablesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //  Load helper functions
        $this->loadHelpers();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //  Register dependent service providers.
        $this->app->register('Amprest\LaravelDatatables\Providers\BladeServiceProvider');
        
        //  Load package file dependancies
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-datatables');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../config/datatables.php', 'datatables');

        //  Allow the config files to be published.
        $this->publishes( [
            __DIR__.'/../config/datatables.php' => config_path('datatables.php') 
        ]);

        //  Allow public assets to be published
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/laravel-datatables'),
        ], 'public');

        //  Register custom package commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\DatatablesInstall::class,
            ]);
        }
    }

    /**
     * Load helper functions.
     *
     * @return void
     */
    protected function loadHelpers()
    {
        foreach (glob(__DIR__.'\Utils\*.php') as $helper) {
            require_once $helper;
        }
    }
}
