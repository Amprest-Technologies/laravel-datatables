<?php

namespace Amprest\LaravelDatatables\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AppController extends Controller 
{
    public function home()
    {
        return package_view('home'); 
    }
}