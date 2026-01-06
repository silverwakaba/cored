<?php

use Illuminate\Support\Facades\Route;

// Controller
// add controller here

// API routing project
Route::prefix('/')->name('be.')->group(function(){
    // Project
    Route::prefix('project')->name('project.')->group(function(){
        // 
    });
});
