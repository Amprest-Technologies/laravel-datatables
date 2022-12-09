<?php

use Amprest\LaravelDatatables\Http\Controllers\AppController;
use Amprest\LaravelDatatables\Http\Controllers\ColumnController;
use Amprest\LaravelDatatables\Http\Controllers\ConfigurationController;
use Amprest\LaravelDatatables\Http\Middleware\LocalEnvironment;
use Illuminate\Support\Facades\Route;

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
Route::name('app.')->prefix('app')->group(function () {
    Route::get('css-assets', [AppController::class, 'css'])->name('css');
    Route::get('js-assets', [AppController::class, 'js'])->name('js');
});

//  Configuration options
Route::middleware([LocalEnvironment::class])->group(function () {
    Route::resource('configurations', ConfigurationController::class)->except(['show']);
    Route::name('configurations.')->prefix('configurations')->group(function () {
        Route::get('css', [ConfigurationController::class, 'css'])->name('css');
        Route::get('js', [ConfigurationController::class, 'js'])->name('js');
        Route::delete('{configuration}/trash', [ConfigurationController::class, 'trash'])->name('trash');
        Route::put('{configuration}/restore', [ConfigurationController::class, 'restore'])->name('restore');
    });

    //  Column configurations
    Route::name('columns.')->prefix('columns')->group(function () {
        Route::post('{configuration}', [ColumnController::class, 'store'])->name('store');
        Route::put('{configuration}/{column}', [ColumnController::class, 'update'])->name('update');
        Route::get('{configuration}/{column}', [ColumnController::class, 'destroy'])->name('destroy');
    });
});
