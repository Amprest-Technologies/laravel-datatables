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
Route::get('/ajax', 'AppController@ajax')->name('ajax');
Route::post('/users', 'AppController@users')->name('users');

//  Manage the datatable configurations
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