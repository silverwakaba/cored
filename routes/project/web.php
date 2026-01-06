<?php

use Illuminate\Support\Facades\Route;

// Controller
// add controller here

// FE routing project
Route::prefix('/')->name('fe.')->middleware(['jwt.global', 'minify.blade'])->group(function(){
    // Project
    Route::name('project.')->group(function(){
        // 
    });
});
