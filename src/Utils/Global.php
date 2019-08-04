<?php

/**
 * Generate a path to the published assets folder.
 *
 * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
 * @param String $path
 * @return String
 */
function package_asset($path)
{
    return asset('vendor/contact'.'/'.$path);
}

/**
 * Generate datatables payload.
 *
 * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
 * @param String $tableID
 * @param String $identifier
 * @return Array 
 */
function datatables_payload($tableID, $identifier = null)
{
    return Datatables::payload($tableID, $identifier = null);
}