<?php

namespace Amprest\LaravelDatatables\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Amprest\LaravelDatatables\Models\DatabaseConfiguration;

class ConfigurationController extends Controller 
{
    public function index()
    {
        return package_view('index'); 
    }
}