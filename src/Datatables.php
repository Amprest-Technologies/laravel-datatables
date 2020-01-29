<?php

namespace Amprest\LaravelDatatables;
use Amprest\LaravelDatatables\Models\Configuration;

class Datatables
{
    /**
     * Generate datatables payload.
     *
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * @param String $tableID
     * @param String $identifier
     * @return Array
     */
    public static function payload($tableID)
    {        
        // 	Define defaults, and fetch configurations
        $configuration = Configuration::identifier($tableID)->first();
        
        //  Check if a configuration was drawn
        if($configuration) return $configuration->payload;

        //  Throw a 404 error otherwise
        abort('404', "The table's identifier, $tableID cannot be found or has been deactivated.");
    }   
}