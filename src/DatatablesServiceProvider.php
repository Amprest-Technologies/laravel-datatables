<?php

namespace Amprest\LaravelDatatables;

use Amprest\LaravelDatatables\Http\Middleware\LocalEnvironment;
use Illuminate\Support\ServiceProvider;

class DatatablesServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {        
        //  Load helper functions
        $this->loadHelpers();
    }

    /**
     * Load helper functions.
     *
     * @return void
     */
    protected function loadHelpers()
    {
        foreach (glob(__DIR__ . DIRECTORY_SEPARATOR . 'Utils' . DIRECTORY_SEPARATOR . '*.php') as $helper) {
            require_once $helper;
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //  Get the root path
        $root = __DIR__ . DIRECTORY_SEPARATOR . '..';

        //  Register default config values
        $this->mergeConfigFrom($root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'package.php', 'package');
        $this->mergeConfigFrom($root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'datatables.php', 'datatables');

        //  Register dependent service providers and router file.
        $this->app->register('Amprest\LaravelDatatables\Providers\BladeServiceProvider');
        $this->app->register('Amprest\LaravelDatatables\Providers\FacadeServiceProvider');
        $this->app['router']->name('datatables.')->prefix('datatables')
            ->middleware(['web', LocalEnvironment::class])
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'routes/web.php');
            });

        //  Load other package file dependancies
        $this->loadViewsFrom($root . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views', config('package.name'));
        $this->loadMigrationsFrom($root . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations');

        //  Allow the config files to be published.
        $this->publishes([
            $root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'datatables.php' => config_path('datatables.php')
        ], 'datatables-config');

        //  Allow public assets to be published
        $this->publishes([
            $root . DIRECTORY_SEPARATOR . 'public' => public_path('vendor' . DIRECTORY_SEPARATOR . '' . config('package.name')),
        ], 'datatables-assets');

        //  Register custom package commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\DatatablesInstall::class,
            ]);
        }
    }
}
