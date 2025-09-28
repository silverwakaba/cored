<?php

use Illuminate\Support\Facades\Route;

// Core Controller
use App\Http\Controllers\FE\Core\Access\PermissionController;
use App\Http\Controllers\FE\Core\Access\RoleController;
use App\Http\Controllers\FE\Core\Auth\GeneralAuthController;
use App\Http\Controllers\FE\Core\Shared\BasedataController;

// General Controller
use App\Http\Controllers\FE\PageController;

// FE routing
Route::prefix('/')->name('fe.')->middleware(['jwt.global', 'minify.blade'])->group(function(){
    // Base-related-thing
    Route::prefix('base')->name('base.')->controller(BasedataController::class)->group(function(){
        // Menu
        Route::get('menu', 'menu')->name('menu');
    });

    // Page without any logic
    Route::prefix('/')->name('page.')->group(function(){
        // PageController
        Route::controller(PageController::class)->group(function(){
            // Index
            Route::get('/', 'index')->name('index');

            // Index auth
            Route::get('auth', 'auth')->name('auth');
        });
    });

    // General Auth
    Route::prefix('auth')->name('auth.')->middleware(['jwt.guest'])->controller(GeneralAuthController::class)->group(function(){
        // Register
        Route::get('register', 'register')->name('register');
        Route::post('register', 'registerPost')->withoutMiddleware(['jwt.guest']);

        // Login
        Route::get('login', 'login')->name('login');
        Route::post('login', 'loginPost')->withoutMiddleware(['jwt.guest']);

        // Logout
        Route::post('logout', 'logout')->name('logout')->middleware(['jwt.fe'])->withoutMiddleware(['jwt.guest']);

        // Validate Token
        Route::get('validate-token', 'validate')->name('validate');
    });

    // Apps
    Route::prefix('apps')->name('apps.')->middleware(['jwt.fe'])->group(function(){
        // Page apps
        Route::get('/', [PageController::class, 'app'])->name('index');

        // Role-Based Access Control
        Route::prefix('rbac')->name('rbac.')->group(function(){
            // Index
            Route::get('/', [PageController::class, 'appRBAC'])->name('index');

            // Role
            Route::prefix('role')->name('role.')->controller(RoleController::class)->group(function(){
                // Index
                Route::get('/', 'index')->name('index');

                // Index
                Route::get('list', 'list')->name('list');

                // Create
                Route::post('create', 'create')->name('create');

                // Read
                Route::get('read/{id}', 'read')->name('read');

                // Sync role to Permission
                Route::post('sync-to-permission/{id}', 'syncToPermission')->name('stp');
            });

            // Permission
            Route::prefix('permission')->name('permission.')->controller(PermissionController::class)->group(function(){
                // Index
                Route::get('/', 'index')->name('index');

                // List
                Route::get('list', 'list')->name('list');

                // Create
                Route::post('create', 'create')->name('create');

                // Read
                Route::get('read/{id}', 'read')->name('read');

                // Update
                Route::post('update/{id}', 'update')->name('update');

                // Delete
                Route::post('delete/{id}', 'delete')->name('delete');
            });
        });
    });
});
