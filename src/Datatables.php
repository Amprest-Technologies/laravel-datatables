<?php

namespace Amprest\LaravelDatatables;

use Exception;
use Amprest\LaravelDatatables\Models\Configuration;

class Datatables
{
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
        if(!$tableID) throw new Exception('Please provide an id attribute to proceed.');

        // 	Define defaults, and fetch configurations
        $this->configuration = $this->configuration->find($tableID);

        //  Check if a configuration was drawn
        if($this->configuration) return $this->configuration['payload'];

        //  If no configurations are available, prevent datatable initialization
        return [];
    }   
}