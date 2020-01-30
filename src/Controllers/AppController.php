<?php

namespace Amprest\LaravelDatatables\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Amprest\LaravelDatatables\Facades\DatatablesAjax;

class AppController extends Controller 
{
    public function home()
    {
        return package_view('home'); 
    }

    public function users()
    {   
        //  Get the model
        return DatatablesAjax::render(User::class);
    }
}