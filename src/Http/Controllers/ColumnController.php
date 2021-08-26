<?php

namespace Amprest\LaravelDatatables\Http\Controllers;

use Str;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Amprest\LaravelDatatables\Models\Configuration;

class ColumnController extends Controller 
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
     * Update a particular table's columns
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $configuration
     * @return \Illuminate\Support\Facades\View
     */
    public function store(Request $request, $configuration)
    {   
        //  Find the configuration
        $configuration = $this->configuration->find($configuration);
        $columns = collect($configuration['columns']);

        //  Validate the request
        Validator::make($request->all(), [
            'name' => [
                'required',
                'max:30',
                'regex:/^[a-zA-Z0-9_ ]*$/',
                function ($attribute, $value, $fail) use ($columns) {
                    if($columns->contains(function($column, $key) use ($value){
                        return strtolower($value) == strtolower($column);
                    })) {
                        $fail('The table column name should be unique');
                    }
                },
            ],
        ], [], [
            'name' => 'column name'
        ])->validate();

        //  Update the payload object
        $payload = $configuration['payload'];
        array_push($payload['filters'], [
            'type' => 'input',
            'data_type' => 'string',
            'title' => ucwords(strtolower($request->name)),
            'server' => $name = Str::slug( strtolower($request->name), '_' ),
            'name' => $name,
        ]);

        //  Merge the processed data items
        $this->configuration->update([
            'identifier' => $configuration['identifier'],
            'columns' => array_unique(array_merge($configuration['columns'], [ $request->name ])),
            'deleted_at' => $configuration['deleted_at'],
            'payload' => $payload,
        ]);

        //  Redirect to the previous page
        return redirect()->back()->with([
            'success' => 'Columns have been updated successfully.'
        ]);
    }

    /**
     * Hard delete a tables column
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * 
     * @param string $configuration
     * @param string $column
     * @return \Illuminate\Support\Facades\View
     */
    public function destroy($configuration, $column)
    {
        //  Find the configuration and permanently delete it
        $configuration = $this->configuration->find($configuration);

        //  Get the payload
        $payload = $configuration['payload'];

        //  Remove any occurrence of the column in the filters
        $payload['filters'] = collect($payload['filters'])->filter(function($option) use ($column){
            return $option['name'] != Str::slug(strtolower($column), '_');
        })->toArray();

        //  Remove any occurrence of the column in the sorting array
        $payload['sorting'] = collect($payload['sorting'])->filter(function($option) use ($column){
            return $option['column'] != Str::slug(strtolower($column), '_');
        })->toArray();

        //  Remove any occurrence of the column in the hidden columns array
        $payload['hiddenColumns'] = array_diff( $payload['hiddenColumns'], [ Str::slug(strtolower($column), '_') ] );

        //  Merge the processed data items
        $this->configuration->update([
            'identifier' => $identifier = $configuration['identifier'],
            'columns' => array_diff( $configuration['columns'], [ $column ] ),
            'deleted_at' => $configuration['deleted_at'],
            'payload' => $payload,
        ]);

        //  Redirect to the configuration index page
        return redirect()->route('datatables.configurations.edit', [
            'configuration' => $identifier
        ])->with([
            'success' => 'The table column has been permanently deleted.'
        ]);                
    }
}