<?php

namespace Amprest\LaravelDatatables;

use Illuminate\Support\Facades\Route;
use Amprest\LaravelDatatables\Models\Configuration;
use Amprest\LaravelDatatables\Traits\HandlesAjaxRequests;

class Datatables
{
    //  Include trait to manage ajax requests
    use HandlesAjaxRequests;

    //  Define the variable
    private $configuration;

    public function __construct()
    {
        $this->configuration = new Configuration();
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
        $this->configuration = $this->configuration->find($tableID);

        //  Check if a configuration was drawn
        if($this->configuration) {
            //  Check for ajax configurations
            $this->checkForAjaxConfigurations();

            //  If everything is ok, return the request
            return array_merge(($this->configuration)['payload'], [
                'datatable' => true,
            ]);
        };

        //  If no configurations are available, prevent datatable initialization
        return [ 'datatable' => false ];
    }   

    /**
     * Check if ajax configurations have been properly defined
     *
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * @return null
     */
    public function checkForAjaxConfigurations()
    {
        //  Get the ajax object payload
        $ajax = ($this->configuration)['payload']['ajax'];

        //  If the ajax object is defined and ajax is enabled
        if(isset($ajax['enabled']) && $ajax['enabled']) {
            //  Check if the route is empty, if not check if its defined
            if(!$route = $ajax['options']['route']) {
                abort('403', "The datatables AJAX route has not been defined.");
            } else if($route && !Route::has($route)) {
                abort('403', "The datatables AJAX route name \"$route\" does not exist.");
            }
        } 
    }
}