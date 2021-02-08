<?php

namespace Amprest\LaravelDatatables\Traits;

use Amprest\LaravelDatatables\Services\FileService;

trait HasAssets
{
    /**
     * Load the css files
     * 
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     */
    public function css()
    {
        //  Return the css file
        return (new FileService)->load($this->cssPath, 'text/css');
    }

    /**t
     * Load the js files
     * 
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     */
    public function js()
    {
        //  Return the js file
        return (new FileService)->load($this->jsPath);
    }
}