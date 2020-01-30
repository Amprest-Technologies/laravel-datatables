<?php

namespace Amprest\LaravelDatatables\Facades;

class Datatables
{
    /**
     *  This function resolves the facade from the app container
     *  @author Alvin Gichira Kaburu
     *  @param string $name
     *  @return class
     */
    protected static function resolveFacade($name)
    {
        return app()->make($name);
    }

    /**
     *  This function class the method from the parent class
     *  passing any relevant arguments
     *  @author Alvin Gichira Kaburu
     *  @param string $method
     *  @param array $arguments
     */
    public static function __callStatic($method, $arguments)
    {
        return ( self::resolveFacade('Datatables') )
            ->$method(...$arguments);
    }
}