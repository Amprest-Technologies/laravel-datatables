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

Route::name('datatables.')->prefix('datatables')->middleware(['web'])->group(function(){
    Route::get('/', function(){
        return view('laravel-datatables::index');
    })->name('index');
});