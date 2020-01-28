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
     * List all table configurations in the database
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * 
     * @return \Illuminate\Support\Facades\View
     */
    public function index()
    {
        return package_view('configurations.index', [
            'configurations' => Configuration::withTrashed()->get(),
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
            'payload' => array_merge([ 'id' => $identifier ], config('datatables')),
            'columns' => [],
        ]);

        //  Validate the request
        $this->validateRequest($request);

        //  Create the configuration
        $configuration = Configuration::create($request->all());

        //  Redirect to the configuration edit page
        return redirect()->route('datatables.configurations.edit', [
            'configuration' => $configuration,
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
        $configuration = Configuration::withTrashed()->identifier($configuration)
            ->firstOrFail();

        return package_view('configurations.edit', [
            'configuration' => $configuration,
            'identifier' => $configuration->identifier, 
            'configurations' => $configuration->payload,
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
    public function update(Request $request, $configuration)
    {
        //  Find the configuration
        $configuration = Configuration::withTrashed()->identifier($configuration)
            ->firstOrFail();

        //  Sanitize the filters element
        $filters = $sorting = $hidden = [];
        
        //  Check if columns exist
        if($request->columns) {
            foreach($request->columns as $column => $options) {
                //  Push options into the filters array
                array_push($filters, [
                    'column' => $column = Str::slug(strtolower($column), '_'),
                    'type' => $options['type'],
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
        $configuration->update([
            'identifier' => $request['configurations']['id'],
            'payload' => array_merge($request->configurations, [
                'filters' => $filters,
                'sorting' => $sorting,
                'hiddenColumns' => $hidden,
            ])
        ]);

        //  Redirect to the configuration edit page
        return redirect()->route('datatables.configurations.edit', [
            'configuration' => $configuration,
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
    public function trash(Configuration $configuration)
    {
        //  Soft delete the configuration
        $configuration->delete();  
        
        //  Redirect to the configuration index page
        return redirect()->route('datatables.configurations.index')->with([
            'success' => 'The table listing has been disabled successfully.'
        ]);
    }

     /**
     * Restore a soft deleted record
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * 
     * @param string $configuration
     * @return \Illuminate\Support\Facades\View
     */
    public function restore($configuration)
    {
        //  Find the configuration and restore it
        $configuration = Configuration::withTrashed()->identifier($configuration)
            ->firstOrFail()->restore();

        //  Redirect to the configuration index page
        return redirect()->route('datatables.configurations.index')->with([
            'success' => 'The table listing has been activated successfully.'
        ]);
    }

    /**
     * Hard delete a record in the database
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * 
     * @param string $configuration
     * @return \Illuminate\Support\Facades\View
     */
    public function destroy($configuration)
    {
        //  Find the configuration and permanently delete it
        $configuration = Configuration::withTrashed()->identifier($configuration)
            ->firstOrFail()->forceDelete();

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
    public function validateRequest(Request $request, Configuration $configuration = null)
    {
        //  Define the unique rule
        $unique = Rule::unique(  with(new Configuration)->getTable() )
            ->ignore($configuration->id ?? null);

        //  Return the validator instance
        return Validator::make($request->all(), [
            'identifier' => [ 'required', 'max:30', 'min:5', $unique ],
            'payload' => [ 'required', 'array' ],
        ])->validate();
    }
}