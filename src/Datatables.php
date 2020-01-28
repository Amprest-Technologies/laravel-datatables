<?php

namespace Amprest\LaravelDatatables;

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
    public static function payload($tableID, $identifier = null)
    {        
        // 	Define defaults, and fetch configurations
        $identifier = $identifier ?: $tableID;
        $data = Self::data($identifier);
        $defaults = Self::defaults($tableID);

        //  Merge the defaults with the determined payload, remove any extra keys
        return (
            collect(array_merge($defaults, $data))->forget('custom')
        )->toArray();
    }   
    
    /**
     * Generate custom datatables data.
     *
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * @param String $identifier
     * @return Array
     */
    public static function data($identifier)
    {
        // Get the customized data as defined by the identifier
        return config('datatables.data.'.$identifier);
    }

    /**
     * Generate the default configurations.
     *
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * @param String $tableID
     * @return Array
     */
    public static function defaults($tableID)
    {
        //  Get the general defaults from the config and append a 
        //  key named exports and id with the defined export values.
        return array_merge([
            'id' => $tableID,
        ], config('datatables.defaults'), [
            'exports' => config('datatables.exports'),
        ]);
    }
}