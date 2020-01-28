<?php

namespace Amprest\LaravelDatatables\Controllers;

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
    public function update(Request $request, $configuration)
    {
        //  Find the configuration
        $configuration = Configuration::withTrashed()->identifier($configuration)
            ->firstOrFail();

        //  Update the columns for the table
        $configuration->update([
            'columns' => array_unique(array_merge($configuration->columns, [ $request->name ]))
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

        //  Remove a column 
        $configuration->update([
            'columns' => array_diff( $configuration->columns, [ $column ] )
        ]);

        //  Redirect to the configuration index page
        return redirect()->route('datatables.configurations.edit', [
            'configuration' => $configuration
        ])->with([
            'success' => 'The table column has been permanently deleted.'
        ]);                
    }
}