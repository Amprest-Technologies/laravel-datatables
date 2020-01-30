<?php

namespace Amprest\LaravelDatatables\Controllers;

use Str;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Amprest\LaravelDatatables\Models\Configuration;

class ColumnController extends Controller 
{
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
        $configuration = Configuration::withTrashed()->identifier($configuration)
            ->firstOrFail();

        //  Update the payload object
        $payload = $configuration->payload;
        array_push($payload['filters'], [
            'type' => '',
            'title' => ucwords(strtolower($request->name)),
            'server' => $name = Str::slug( strtolower($request->name), '_' ),
            'column' => $name,
        ]);

        //  Update the columns for the table
        $configuration->update([
            'columns' => array_unique(array_merge($configuration->columns, [ $request->name ])),
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
        $configuration = Configuration::withTrashed()->identifier($configuration)
            ->firstOrFail();

        //  Get the payload
        $payload = $configuration->payload;

        //  Remove any occurrence of the column in the filters
        $payload['filters'] = collect($payload['filters'])->filter(function($option) use ($column){
            return $option['column'] != Str::slug(strtolower($column), '_');
        })->toArray();

        //  Remove any occurrence of the column in the sorting array
        $payload['sorting'] = collect($payload['sorting'])->filter(function($option) use ($column){
            return $option['column'] != Str::slug(strtolower($column), '_');
        })->toArray();

        //  Remove any occurrence of the column in the hidden columns array
        $payload['hiddenColumns'] = array_diff( $payload['hiddenColumns'], [ Str::slug(strtolower($column), '_') ] );

        //  Finally remove the column 
        $configuration->update([
            'columns' => array_diff( $configuration->columns, [ $column ] ),
            'payload' => $payload,
        ]);

        //  Redirect to the configuration index page
        return redirect()->route('datatables.configurations.edit', [
            'configuration' => $configuration
        ])->with([
            'success' => 'The table column has been permanently deleted.'
        ]);                
    }
}