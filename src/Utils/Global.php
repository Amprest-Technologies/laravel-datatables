<?php

use Amprest\LaravelDatatables\Facades\Datatables;

/**
 * Generate a path to the published assets folder.
 *
 * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
 *
 * @param  string  $path
 * @return string
 */
function package_asset($path)
{
    return asset('vendor/'.config('package.name').'/'.$path);
}

/**
 * Generate a view name depending on the package.
 *
 * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
 *
 * @param  string  $view
 * @return string
 */
function package_view($view, $payload = [])
{
    return view(config('package.name').'::'.$view, $payload);
}

/**
 * Generate a package resouce view name depending on the package.
 *
 * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
 *
 * @param  string  $path
 * @return string
 */
function package_resource($path)
{
    return config('package.name').'::'.$path;
}

/**
 * Generate datatables payload.
 *
 * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
 *
 * @param  string  $tableID
 * @return array
 */
function datatables_payload($tableID)
{
    return Datatables::payload($tableID);
}
