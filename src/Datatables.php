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
        $defaults = Self::defaults($tableID, $identifier);

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
     * @param String $identifier
     * @return Array
     */
    public static function defaults($tableID, $identifier)
    {
        //  Get the general defaults from the config and append a 
        //  key named exports and id with the defined export values.
        return array_merge(config('datatables.defaults'),[
            'id' => $tableID,
            'exports' => Self::exports($identifier),
        ]);
    }

    /**
     * Generate the export options for the datatables component.
     *
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * @param String $identifier
     * @return Array
     */
    public static function exports($identifier)
    {
        //  Determine the default options
        $options = Self::options();
        $data = Self::data($identifier);

        //  Determine the default export values, for each export value append a 
        //  default option array merged with values that the user has defined.
        $exports = config('datatables.exports');
        foreach($exports as $key => $export) {
            $export = array_merge($export, $data['custom']['exports'][$key] ?? []);
            //  Define the top and bottom messsage
            $export['options']['messageTop'] = $data['custom']['message_top'] ?? '';
            $export['options']['messageBottom'] = $data['custom']['message_bottom'] ?? '';
            $export['options'] = array_merge($options, $export['options']);
            $exports[$key] = $export;
        }
        
        //  Return the refined exports
        return $exports;
    }

    /**
     * Generate the custom export options.
     *
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * @return Array
     */
    public static function options()
    {
        return config('datatables.options');
    }
}