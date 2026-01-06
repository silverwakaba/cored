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
        });
    });
});
