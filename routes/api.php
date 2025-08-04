<?php

use Illuminate\Support\Facades\Route;

// Controller
use App\Http\Controllers\API\Core\Auth\JwtController;
use App\Http\Controllers\API\Core\Access\MenuController;
use App\Http\Controllers\API\Core\Access\PermissionController;
use App\Http\Controllers\API\Core\Access\RoleController;
use App\Http\Controllers\API\Core\Access\UserAccessController;

// API routing
Route::prefix('/')->name('be.')->group(function(){
    // Core
    Route::prefix('core')->name('core.')->group(function(){
        // Auth
        Route::prefix('auth')->name('auth.')->group(function(){
            // JWT Auth
            Route::prefix('jwt')->name('jwt.')->controller(JwtController::class)->group(function(){
                // Register
                Route::post('register', 'register')->name('register');

                // Login
                Route::post('login', 'login')->name('login');

                // Logout
                Route::post('logout', 'logout')->name('logout')->middleware(['jwt.be']);

                // JWT Token
                Route::prefix('token')->name('token.')->middleware(['jwt.be'])->group(function(){
                    // Validate
                    Route::get('validate', 'validateToken')->name('validate');

                    // Create
                    Route::post('create', 'createToken')->name('create');
                });
            });

            // OAuth2
            // TBA
        });

        // Menu
        Route::prefix('menu')->name('menu.')->controller(MenuController::class)->group(function(){
            // Index
            Route::get('/', 'index')->name('index');
        });

        // Role and Permission
        Route::prefix('rnp')->name('rnp.')->middleware(['jwt.be', 'role:Root'])->group(function(){
            // Role
            Route::prefix('role')->name('role.')->controller(RoleController::class)->group(function(){
                // List
                Route::get('list', 'list')->name('list');

                // Create
                Route::post('create', 'create')->name('create');

                // Read
                Route::get('read/{id}', 'read')->name('read');

                // Sync to Permission
                Route::post('sync-to-permission/{id}', 'syncToPermission')->name('stp');

                // Sync to User
                Route::post('sync-to-user/{id}', 'syncToUser')->name('stu');
            });

            // Permission
            Route::prefix('permission')->name('permission.')->controller(PermissionController::class)->group(function(){
                // List
                Route::get('list', 'list')->name('list');

                // Create
                Route::post('create', 'create')->name('create');

                // Read
                Route::get('read/{id}', 'read')->name('read');

                // Delete
                Route::post('delete/{id}', 'delete')->name('delete');
            });

            // User Access Control
            Route::prefix('uac')->name('uac.')->controller(UserAccessController::class)->group(function(){
                // List
                Route::get('list', 'list')->name('list');

                // Create
                Route::post('create', 'create')->name('create');

                // Public UAC
                Route::withoutMiddleware(['role:Root'])->group(function(){
                    // Read
                    Route::get('read/{id}', 'read')->name('read');

                    // Update
                    Route::post('update', 'update')->name('update');

                    // Activation
                    Route::post('activation/{id}', 'activation')->name('activation')->middleware(['role:Root|Admin']);
                });
            });
        });
    });
});
