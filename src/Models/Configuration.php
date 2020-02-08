<?php

namespace Amprest\LaravelDatatables\Models;

use Illuminate\Support\Facades\Storage;

class Configuration
{
    //  Define the data variable
    private $data;
    private $filepath = 'datatables-configurations.json';

    /**
    * Initialize the model
    *
    * @author Alvin Gichira Kaburu
    * @return null
    *
    */
    public function __construct()
    {
        $this->data = collect(json_decode(file_get_contents(
            storage_path('app'.'/'.$this->filepath)
        ), TRUE));
    }

    /**
    * Create a method to return all items from the data store
    *
    * @author Alvin Gichira Kaburu
    * @return object
    *
    */
    public static function all()
    {
        //  Get all configurations
        $configurations = json_decode(file_get_contents(
            storage_path('app/datatables-configurations.json')
        ), TRUE);

        //  Cast the configurations into an object
        return json_decode(json_encode($configurations));
    }

    /**
    * Create a new table configration
    *
    * @author Alvin Gichira Kaburu
    * @return object
    *
    */
    public static function create(array $data)
    {
        //  Get all configurations
        $configurations = json_decode(file_get_contents(
            storage_path('app/datatables-configurations.json')
        ), TRUE);

        $configurations = collect($configurations)
            ->push( $data = collect($data)->except('_token') )
            ->toArray();

        //  Insert the item into the json file
        Storage::put('datatables-configurations.json', json_encode($configurations));

        //  Cast the configurations into an object
        return $data;
    }

    /**
    * Create a method to return a specific item from the data store
    *
    * @author Alvin Gichira Kaburu
    * @param integer $identifier
    * @return \Illuminate\Support\Collection
    *
    */
    public function find($identifier)
    {
        return $this->data->where('identifier', $identifier)->first();
    }

    /**
    * Create a method to conditionally return items from the data store
    *
    * @author Alvin Gichira Kaburu
    * @param string $key
    * @param string $value
    * @return $this
    *
    */
    public function where($key, $value)
    {
        $this->data = $this->data->where($key, $value);
        return $this;
    }

    /**
    * Create a method to return items from the data store
    *
    * @author Alvin Gichira Kaburu
    * @return $object
    *
    */
    public function get()
    {
        return json_decode(json_encode($this->data));
    }

    /**
    * Update the configurations
    *
    * @author Alvin Gichira Kaburu
    * @return boolean
    *
    */
    public function update($configuration)
    {
        //  Get all configurations
        $configurations = $this->data;
        $configurations = $configurations->map(function($item, $index) use ($configuration){
            if($item['identifier'] == $configuration['identifier']) {
                $item = $configuration;
            }
            return $item;
        });
        
        //  Insert the item into the json file
        return Storage::put($this->filepath, json_encode($configurations));
    }

    /**
    * Delete the configurations
    *
    * @author Alvin Gichira Kaburu
    * @return boolean
    *
    */
    public function delete($identifier)
    {
        //  Get all configurations
        $configurations = $this->data;
        $configurations = $configurations->map(function($configuration, $index) use ($identifier){
            if($configuration['identifier'] == $identifier) {
                $configuration['deleted_at'] = now()->toDateTimeString();
            }
            return $configuration;
        });
        
        //  Insert the item into the json file
        return Storage::put($this->filepath, json_encode($configurations));
    }

    /**
    * Restore a configuration
    *
    * @author Alvin Gichira Kaburu
    * @return boolean
    *
    */
    public function restore($identifier)
    {
        //  Get all configurations
        $configurations = $this->data;
        $configurations = $configurations->map(function($configuration, $index) use ($identifier){
            if($configuration['identifier'] == $identifier) {
                $configuration['deleted_at'] = null;
            }
            return $configuration;
        });
        
        //  Insert the item into the json file
        return Storage::put($this->filepath, json_encode($configurations));
    }

    /**
    * Force delete a configuration
    *
    * @author Alvin Gichira Kaburu
    * @return boolean
    *
    */
    public function forceDelete($identifier)
    {
        //  Get all configurations
        $configurations = $this->data;
        $configurations = $configurations->filter(function($configuration, $index) use ($identifier){
            return $configuration['identifier'] != $identifier;
        })->values();
        
        //  Insert the item into the json file
        return Storage::put($this->filepath, json_encode($configurations));
    }
}
