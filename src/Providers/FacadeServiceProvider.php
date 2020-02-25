<?php

namespace Amprest\LaravelDatatables\Providers;

use Illuminate\Support\ServiceProvider;
use Amprest\LaravelDatatables\Datatables;

class FacadeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('Datatables', function($app) {
            return new Datatables();
        });
    }
}
