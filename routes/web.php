<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the package. These
| routes are loaded by the Package ServiceProvider.
|
*/

//  Display a sample datatable
Route::get('/', 'AppController@home')->name('home');

//  Manage the datatable configurations
Route::resource('configurations', 'ConfigurationController');