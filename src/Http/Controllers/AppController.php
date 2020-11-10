<?php

namespace Amprest\LaravelDatatables\Http\Controllers;

use App\User;
use Illuminate\Routing\Controller;
use Amprest\LaravelDatatables\Facades\Datatables;

class AppController extends Controller 
{
    /**
     *  Return the sample users as datatables json object
     *  @author Alvin Gichira Kaburu
     *  @return Illuminate\Http\Response
     */
    public function users()
    {   
        return Datatables::renderAjax( User::query() );
    }
}