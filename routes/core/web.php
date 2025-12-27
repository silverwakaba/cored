<?php

use Illuminate\Support\Facades\Route;

// Core Controller
use App\Http\Controllers\Core\FE\Access\PermissionController;
use App\Http\Controllers\Core\FE\Access\RoleController;
use App\Http\Controllers\Core\FE\Access\UserAccessController;
use App\Http\Controllers\Core\FE\Auth\GeneralAuthController;

use App\Http\Controllers\Core\FE\Shared\BasedataController;
use App\Http\Controllers\Core\FE\Shared\BaseModuleController;
use App\Http\Controllers\Core\FE\Shared\BaseRequestController;
use App\Http\Controllers\Core\FE\Shared\CallToActionController;

// General Controller
use App\Http\Controllers\Core\FE\PageController;

// FE routing
Route::prefix('/')->name('fe.')->middleware(['jwt.global', 'minify.blade'])->group(function(){
    // Root page without any logic (reserved: /base, /auth, /apps, /cta)
    Route::prefix('/')->name('page.')->group(function(){
        // PageController
        Route::controller(PageController::class)->group(function(){
            // Index
            Route::get('/', 'index')->name('index');

            // Index auth
            Route::get('auth', 'auth')->name('auth'); // => this one is masking as /auth root

            // Index cta
            Route::get('cta', 'cta')->name('cta'); // => this one is masking as /cta root
        });
    });

    // CTA
    Route::prefix('cta')->name('cta.')->controller(CallToActionController::class)->group(function(){
        // Messages
        Route::get('message', 'message')->name('message');
        Route::post('message', 'messagePost');
    });

    // General Auth (core - do not touch)
    Route::prefix('auth')->name('auth.')->middleware(['jwt.guest'])->controller(GeneralAuthController::class)->group(function(){
        // Register
        Route::get('register', 'register')->name('register');
        Route::post('register', 'registerPost')->withoutMiddleware(['jwt.guest']);

        // Login
        Route::get('login', 'login')->name('login');
        Route::post('login', 'loginPost')->withoutMiddleware(['jwt.guest']);

        // Logout
        Route::post('logout', 'logout')->name('logout')->middleware(['jwt.fe'])->withoutMiddleware(['jwt.guest']);

        // Account verification
        Route::get('verify-account', 'verifyAccount')->name('verify-account');
        Route::post('verify-account', 'verifyAccountPost');
        Route::get('verify-account/{token}', 'verifyAccountTokenized')->name('verify-account-tokenized')->middleware(['signed']);

        // Reset Password
        Route::get('reset-password', 'resetPassword')->name('reset-password');
        Route::post('reset-password', 'resetPasswordPost');
        
        // Reset Password via token
        Route::get('reset-password/{token}', 'resetPasswordTokenized')->name('reset-password-tokenized')->middleware(['signed']);
        Route::post('reset-password/{token}', 'resetPasswordTokenizedPost');

        // Validate Token
        Route::get('validate-token', 'validate')->name('validate');
    });

    // Apps
    Route::prefix('apps')->name('apps.')->middleware(['jwt.fe'])->group(function(){
        // Page apps
        Route::get('/', [PageController::class, 'app'])->name('index');

        // Base-related-thing
        Route::prefix('base')->name('base.')->group(function(){
            // Index
            Route::get('/', [PageController::class, 'appBase'])->name('index');

            // General
            Route::prefix('general')->name('general.')->controller(BasedataController::class)->group(function(){
                // Boolean
                Route::get('boolean', 'boolean')->name('boolean')->withoutMiddleware(['jwt.fe']);
            });

            // Module
            Route::prefix('module')->name('module.')->controller(BaseModuleController::class)->group(function(){
                // Index
                Route::get('/', 'index')->name('index');

                // List
                Route::get('list', 'list')->name('list')->withoutMiddleware(['jwt.fe']);

                // Create
                Route::post('/', 'create')->name('store');

                // Read
                Route::get('/{id}', 'read')->name('show');

                // Update
                Route::put('/{id}', 'update')->name('update');
                Route::patch('/{id}', 'update')->name('update');

                // Delete
                Route::delete('/{id}', 'delete')->name('destroy');
            });

            // Request
            Route::prefix('request')->name('request.')->controller(BaseRequestController::class)->group(function(){
                // Index
                Route::get('/', 'index')->name('index');
                
                // List
                Route::get('list', 'list')->name('list')->withoutMiddleware(['jwt.fe']);

                // Create
                Route::post('/', 'create')->name('store');

                // Read
                Route::get('/{id}', 'read')->name('show');

                // Update
                Route::put('/{id}', 'update')->name('update');
                Route::patch('/{id}', 'update')->name('update');

                // Delete
                Route::delete('/{id}', 'delete')->name('destroy');
            });
        });

        // Role-Based Access Control (core - do not touch)
        Route::prefix('rbac')->name('rbac.')->middleware(['role:Root|Admin|Moderator'])->group(function(){
            // Index
            Route::get('/', [PageController::class, 'appRBAC'])->name('index');

            // Role
            Route::prefix('role')->name('role.')->controller(RoleController::class)->group(function(){
                // Index
                Route::get('/', 'index')->name('index');

                // List
                Route::get('list', 'list')->name('list');

                // Create
                Route::post('/', 'create')->name('store');

                // Read
                Route::get('/{id}', 'read')->name('show');

                // Sync role to Permission
                Route::post('/{id}/sync-to-permission', 'syncToPermission')->name('sync_to_permission');
            });

            // Permission
            Route::prefix('permission')->name('permission.')->controller(PermissionController::class)->group(function(){
                // Index
                Route::get('/', 'index')->name('index');

                // List
                Route::get('list', 'list')->name('list');

                // Create
                Route::post('/', 'create')->name('store');

                // Read
                Route::get('/{id}', 'read')->name('show');

                // Update
                Route::put('/{id}', 'update')->name('update');
                Route::patch('/{id}', 'update')->name('update');

                // Delete
                Route::delete('/{id}', 'delete')->name('destroy');
            });

            // User Access Control
            Route::prefix('uac')->name('uac.')->controller(UserAccessController::class)->group(function(){
                // Index
                Route::get('/', 'index')->name('index');

                // List
                Route::get('list', 'list')->name('list');

                // Create
                Route::post('/', 'create')->name('store');

                // Read
                Route::get('/{id}', 'read')->name('show');

                // Update
                Route::put('/{id}', 'update')->name('update');
                Route::patch('/{id}', 'update')->name('update');

                // Delete
                Route::delete('/{id}', 'delete')->name('destroy');
            });
        });
    });
});
