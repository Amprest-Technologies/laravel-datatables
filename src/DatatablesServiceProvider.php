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
        //  Register default config values
        $this->mergeConfigFrom(__DIR__.'/../config/package.php', 'package');
         $this->mergeConfigFrom(__DIR__.'/../config/datatables.php', 'datatables');

        //  Register dependent service providers.
        $this->app->register('Amprest\LaravelDatatables\Providers\BladeServiceProvider');
        $this->app->register('Amprest\LaravelDatatables\Providers\FacadeServiceProvider');
        
        $routePrefix = config('datatables.route.prefix');
        $this->app['router']
            ->name('datatables.')
            ->prefix( ( $routePrefix ? $routePrefix.'/' : '' ).'datatables')
            ->namespace('Amprest\\LaravelDatatables\\Controllers')
            ->middleware([ 'web' ])
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
            });

        //  Load other package file dependancies
        $this->loadViewsFrom(__DIR__.'/../resources/views', config('package.name'));
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        //  Allow the config files to be published.
        $this->publishes( [
            __DIR__.'/../config/datatables.php' => config_path('datatables.php') 
        ], 'datatables-config');

        //  Allow public assets to be published
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/'.config('package.name')),
        ], 'datatables-assets');

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
