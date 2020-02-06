<?php

use Amprest\LaravelDatatables\Facades\Datatables;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the package. These
| routes are loaded by the Package ServiceProvider.
|
*/

//  Manage the datatable configurations
Route::get('/', 'AppController@home')->name('home');
Route::post('/users', 'AppController@users')->name('users');

Route::resource('configurations', 'ConfigurationController');
Route::name('configurations.')->prefix('configurations')->group(function(){
    Route::delete('/{configuration}/trash', 'ConfigurationController@trash')->name('trash');
    Route::put('/{configuration}/restore', 'ConfigurationController@restore')->name('restore');
});

//  Manage columns
Route::name('columns.')->prefix('columns')->group(function(){
    Route::post('/{configuration}', 'ColumnController@store')->name('store');
    Route::delete('/{configuration}/{column}', 'ColumnController@destroy')->name('destroy');
});
