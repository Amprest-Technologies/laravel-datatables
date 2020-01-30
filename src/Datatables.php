<?php

namespace Amprest\LaravelDatatables;

use Illuminate\Http\Request;
use Amprest\LaravelDatatables\Models\Configuration;
use Amprest\LaravelDatatables\Traits\HandlesAjaxRequests;

class Datatables
{
    //  Include trait to manage ajax requests
    use HandlesAjaxRequests;

    /**
     *  Initialize the class instance
     *  @author Alvin Gichira Kaburu
     *  @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Generate datatables payload.
     *
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * @param String $tableID
     * @param String $identifier
     * @return Array
     */
    public function payload($tableID)
    {        
        // 	Define defaults, and fetch configurations
        $configuration = Configuration::identifier($tableID)->first();
        
        //  Check if a configuration was drawn
        if($configuration) return $configuration->payload;

        //  Throw a 404 error otherwise
        abort('404', "The table's identifier, $tableID cannot be found or has been deactivated.");
    }   
}