<?php

use Amprest\LaravelDatatables\Http\Controllers\AppController;
use Amprest\LaravelDatatables\Http\Controllers\ColumnController;
use Amprest\LaravelDatatables\Http\Controllers\ConfigurationController;

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
Route::post('/users', [AppController::class, 'users'])->name('users');
Route::get('/css-assets', [AppController::class, 'loadCss'])->name('css.load');
Route::get('/js-assets', [AppController::class, 'loadJs'])->name('js.load');

Route::resource('configurations', ConfigurationController::class);
Route::name('configurations.')->prefix('configurations')->group(function(){
    Route::delete('/{configuration}/trash', [ConfigurationController::class, 'trash'])->name('trash');
    Route::put('/{configuration}/restore', [ConfigurationController::class, 'restore'])->name('restore');
});

//  Manage columns
Route::name('columns.')->prefix('columns')->group(function(){
    Route::post('/{configuration}', [ColumnController::class, 'store'])->name('store');
    Route::get('/{configuration}/{column}', [ColumnController::class, 'destroy'])->name('destroy');
});
