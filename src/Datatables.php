<?php

namespace Amprest\LaravelDatatables;

use Exception;
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
     * @param string $tableID
     * @param string $identifier
     * @return array
     */
    public function payload($tableID = null): array
    {     
        // Throw an exception if the id is not provided 
        if(!$tableID) {
            throw new Exception(
                'Please provide an id attribute to proceed.'
            );
        }

        // 	Define defaults, and fetch configurations
        $this->configuration = $this->configuration->find($tableID);

        //  Check if a configuration was drawn
        if($this->configuration) {
            //  Check for ajax configurations
            $this->checkForAjaxConfigurations();

            //  If everything is ok, return the request
            return $this->configuration['payload'];
        };

        //  If no configurations are available, prevent datatable initialization
        return [];
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