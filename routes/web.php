<?php

use Illuminate\Support\Facades\Route;

// Core Controller
use App\Http\Controllers\FE\Core\Access\PermissionController;
use App\Http\Controllers\FE\Core\Access\RoleController;
use App\Http\Controllers\FE\Core\Access\UserAccessController;
use App\Http\Controllers\FE\Core\Auth\GeneralAuthController;
use App\Http\Controllers\FE\Core\Shared\BasedataController;
use App\Http\Controllers\FE\Core\Shared\CallToActionController;

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
            Route::get('auth', 'auth')->name('auth'); // => this one is masking as /auth root

            // Index cta
            Route::get('cta', 'cta')->name('cta'); // => this one is masking as /cta root
        });

        // Root page (reserved: /base, /auth, /apps, /cta)
        // => Add something
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

        // // Feature outside core component (e.g: new project under cored branch as monorepo)
        // Route::prefix('feature')->name('feature.')->group(function(){
        //     // 
        // });

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

                // Activation
                Route::post('/{id}/activation', 'activation')->name('activation');
            });
        });
    });
});
