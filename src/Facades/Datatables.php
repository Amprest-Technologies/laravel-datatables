<?php

namespace Amprest\LaravelDatatables\Facades;

use Amprest\LaravelDatatables\Datatables as LaravelDatatable;
use Illuminate\Support\Facades\Facade;

class Datatables extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     * @author Alvin G. Kaburu <geekaburu@amprest.co.ke>
     */
    protected static function getFacadeAccessor(): string
    { 
        return LaravelDatatable::class; 
    }
}