<?php

namespace Amprest\LaravelDatatables\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Amprest\LaravelDatatables\DatatablesAjax;

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
    public function boot(Request $request)
    {
        $this->app->singleton( 'DatatablesAjax', function($app) use ($request) {
            return new DatatablesAjax($request);
        });
    }
}
