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

Route::get('/', 'ConfigurationController@index')->name('index');