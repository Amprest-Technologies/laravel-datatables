<?php

namespace Amprest\LaravelDatatables\Controllers;

use Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Amprest\LaravelDatatables\Models\Configuration;

class ConfigurationController extends Controller 
{
    /**
     * Initialize the constructor
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * 
     */
    public function __construct()
    {
        $this->configuration = new Configuration();
    }

    /**
     * List all table configurations in the database
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * 
     * @return \Illuminate\Support\Facades\View
     */
    public function index()
    {
        return package_view('configurations.index', [
            'configurations' => Configuration::all(),
        ]); 
    }

    /**
     * Store a new table configuration in the database
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Facades\View
     */
    public function store(Request $request)
    {        
        //  Merge the request parameters
        $request->merge([ 
            'identifier' => $identifier = Str::slug($request->identifier),
            'payload' => array_merge([ 'id' => $identifier ], config('datatables.config')),
            'columns' => [],
            'deleted_at' => null,
        ]);

        //  Validate the request
        Validator::make($request->all(), [
            'payload' => [ 'required', 'array' ],
            'identifier' => [
                'required',
                'max:30',
                'regex:/^[A-Za-z]+[\w\-\:\.]*$/',
                function ($attribute, $value, $fail) {
                    $configurations = collect(Configuration::all());
                    if($configurations && $configurations->isNotEmpty() && $configurations->where('identifier', $value)->first()) {              
                        $fail('The table identifier should be unique');
                    }
                },
            ],
        ])->validate();

        //  Create the configuration
        $configuration = Configuration::create($request->all());

        //  Redirect to the configuration edit page
        return redirect()->route('datatables.configurations.edit', [
            'configuration' => $identifier,
        ])->with([
            'success' => 'The table has been listed successfully.'
        ]);
    }

    /**
     * Edit/Show a particular table configuration from the database
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * 
     * @param string $configuration
     * @return \Illuminate\Support\Facades\View
     */
    public function edit($configuration)
    {
        //  Find the configuration
        $configuration = $this->configuration->find($configuration);

        return package_view('configurations.edit', [
            'configuration' => $configuration,
            'identifier' => $configuration['identifier'], 
            'configurations' => $configuration['payload'],
        ]); 
    }

    /**
     * Update a particular table configuration in the database
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $configuration
     * @return \Illuminate\Support\Facades\View
     */
    public function update(Request $request, $identifier)
    {
        //  Find the configuration
        $configuration = $this->configuration->find($identifier);

        //  Sanitize the filters element
        $filters = $sorting = $hidden = [];
        
        //  Check if columns exist
        if($request->columns) {
            foreach($request->columns as $column => $options) {
                //  Push options into the filters array
                array_push($filters, [
                    'name' => $column = Str::slug(strtolower($column), '_'),
                    'type' => $options['type'],
                    'title' => $options['title'],
                    'server' => $options['server'],
                ]);
    
                //  Push filters into the sorting array
                array_push($sorting, [
                    'column' => $column,
                    'order' => $options['sorting']
                ]);
    
                //  Push hidden columns
                if($options['hidden']) {
                    array_push($hidden, $column);
                }
            }
        }

        //  Merge the processed data items
        $this->configuration->update([
            'identifier' => $identifier,
            'columns' => $configuration['columns'] ?? [],
            'deleted_at' => $request->deleted_at,
            'payload' => array_merge($request->configurations, [
                'id' => $identifier,
                'filters' => $filters,
                'sorting' => $sorting,
                'hiddenColumns' => $hidden,
            ])
        ]);

        //  Redirect to the configuration edit page
        return redirect()->route('datatables.configurations.edit', [
            'configuration' => $identifier,
        ])->with([
            'success' => 'The table configurations have been updated successfully.'
        ]);
    }

    /**
     * Soft delete a record in the database
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * 
     * @param \Amprest\LaravelDatatables\Models\Configuration $configuration
     * @return \Illuminate\Support\Facades\View
     */
    public function trash($identifier)
    {
        //  Soft delete the configuration
        $this->configuration->delete($identifier);  
        
        //  Redirect to the configuration index page
        return redirect()->route('datatables.configurations.index')->with([
            'success' => 'The table listing has been disabled successfully.'
        ]);
    }

     /**
     * Restore a soft deleted record
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * 
     * @param string $identifier
     * @return \Illuminate\Support\Facades\View
     */
    public function restore($identifier)
    {
        //  Find the configuration and restore it
        $this->configuration->restore($identifier);  

        //  Redirect to the configuration index page
        return redirect()->route('datatables.configurations.index')->with([
            'success' => 'The table listing has been activated successfully.'
        ]);
    }

    /**
     * Hard delete a record in the database
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * 
     * @param string $identifier
     * @return \Illuminate\Support\Facades\View
     */
    public function destroy($identifier)
    {
        //  Find the configuration and permanently delete it
        $this->configuration->forceDelete($identifier);

        //  Redirect to the configuration index page
        return redirect()->route('datatables.configurations.index')->with([
            'success' => 'The table listing has been permanently deleted.'
        ]);                
    }

    /**
     *  Validate the request.
     *
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * @param  \Illuminate\Http\Request  $request
     * @param \Amprest\LaravelDatatables\Models\Configuration $configuration
     * @return \Illuminate\Http\Response
     */
    public function validateRequest(Request $request)
    {       
        //  Return the validator instance
        return Validator::make($request->all(), [
            'identifier' => [ 'required', 'max:30', 'min:5' ],
            'payload' => [ 'required', 'array' ],
        ])->validate();
    }
}