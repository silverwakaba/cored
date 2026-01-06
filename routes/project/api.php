<?php

use Illuminate\Support\Facades\Route;

// Controller
use App\Http\Controllers\Project\API\SupplierController;

// API routing project
Route::prefix('/')->name('be.')->group(function(){
    // Project
    Route::prefix('project')->name('project.')->group(function(){
        // Supplier
        Route::prefix('supplier')->name('supplier.')->controller(SupplierController::class)->group(function(){
            // Index
            Route::get('/', 'list')->name('index');

            // Create
            Route::post('/', 'create')->name('store');

            // Read
            Route::get('/{id}', 'read')->name('show');

            // // Update
            // Route::put('/{id}', 'update')->name('update');
            // Route::patch('/{id}', 'update')->name('update');
        });
    });
});
