<?php

use Amprest\LaravelDatatables\Facades\Datatables;

/**
 * Generate a path to the published assets folder.
 *
 * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
 * @param String $path
 * @return String
 */
function package_asset($path)
{
    return asset('vendor/'.config('package.name').'/'.$path);
}

/**
 * Generate a view name depending on the package.
 *
 * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
 * @param String $view
 * @return String
 */
function package_view($view, $payload = array())
{
    return view(config('package.name').'::'.$view, $payload);
}

/**
 * Generate a package resouce view name depending on the package.
 *
 * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
 * @param String $path
 * @return String
 */
function package_resource($path)
{
    return config('package.name').'::'.$path;
}

/**
 * Generate datatables payload.
 *
 * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
 * @param String $tableID
 * @return Array 
 */
function datatables_payload($tableID)
{
    return Datatables::payload($tableID);
}