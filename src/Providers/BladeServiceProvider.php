<?php

namespace Amprest\LaravelDatatables\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
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
        //  Include a customized alias for the datatables component
        Blade::component(package_resource('components.datatables'), 'datatable');
    }
}
