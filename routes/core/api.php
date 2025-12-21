<?php

use Illuminate\Support\Facades\Route;

// Controller
use App\Http\Controllers\Core\API\Auth\JwtController;
use App\Http\Controllers\Core\API\Access\MenuController;
use App\Http\Controllers\Core\API\Access\PermissionController;
use App\Http\Controllers\Core\API\Access\RoleController;
use App\Http\Controllers\Core\API\Access\UserAccessController;
use App\Http\Controllers\Core\API\Shared\CallToActionController;

// API routing
Route::prefix('/')->name('be.')->group(function(){
    // Core
    Route::prefix('core')->name('core.')->group(function(){
        // CTA
        Route::prefix('cta')->name('cta.')->middleware(['throttle:general'])->controller(CallToActionController::class)->group(function(){
            // Messages - 10 attempts per 5 minutes to prevent spam
            Route::post('message', 'message')->name('message')->middleware(['throttle:10,5']);
        });

        // Auth
        Route::prefix('auth')->name('auth.')->group(function(){
            // JWT Auth
            Route::prefix('jwt')->name('jwt.')->controller(JwtController::class)->group(function(){
                // Register - 3 attempts per 15 minutes
                Route::post('register', 'register')->name('register')->middleware(['throttle:3,15']);

                // Login - 5 attempts per 15 minutes
                Route::post('login', 'login')->name('login')->middleware(['throttle:5,15']);

                // Logout
                Route::post('logout', 'logout')->name('logout')->middleware(['jwt.be', 'throttle:api']);

                // Verify account - 3 attempts per 10 minutes
                Route::post('verify-account', 'verifyAccount')->name('verify-account')->middleware(['throttle:3,10']);
                Route::post('verify-account/{token}', 'verifyAccountTokenized')->name('verify-account-tokenized')->middleware(['throttle:3,10']);

                // Reset password - 3 attempts per 10 minutes
                Route::post('reset-password', 'resetPassword')->name('reset-password')->middleware(['throttle:3,10']);
                Route::post('reset-password/{token}', 'resetPasswordTokenized')->name('reset-password-tokenized')->middleware(['throttle:3,10']);

                // JWT Token
                Route::prefix('token')->name('token.')->middleware(['jwt.be', 'throttle:api'])->group(function(){
                    // Validate
                    Route::get('validate', 'validateToken')->name('validate');

                    // Create
                    Route::post('create', 'createToken')->name('create');
                });
            });
        });

        // Menu
        Route::prefix('menu')->name('menu.')->middleware(['throttle:api'])->controller(MenuController::class)->group(function(){
            // Index
            Route::get('/', 'index')->name('index');

            // Test
            Route::post('test', 'test')->name('test');
        });

        // Role-based access control
        Route::prefix('rbac')->name('rbac.')->middleware(['jwt.be', 'role:Root|Admin|Moderator', 'throttle:sensitive'])->group(function(){
            // Role
            Route::prefix('role')->name('role.')->controller(RoleController::class)->group(function(){
                // List
                Route::get('/', 'list')->name('index');

                // Create
                Route::post('/', 'create')->name('store');

                // Read
                Route::get('/{id}', 'read')->name('show');

                // Sync to Permission
                Route::post('/{id}/sync-to-permission', 'syncToPermission')->name('sync_to_permission');

                // Sync to User
                Route::post('/{id}/sync-to-user', 'syncToUser')->name('sync_to_user');
            });

            // Permission
            Route::prefix('permission')->name('permission.')->controller(PermissionController::class)->group(function(){
                // List
                Route::get('/', 'list')->name('index');

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
                // List
                Route::get('/', 'list')->name('index');

                // Create
                Route::post('/', 'create')->name('store');

                // Public UAC
                Route::withoutMiddleware(['role:Root'])->group(function(){
                    // Read
                    Route::get('/{id}', 'read')->name('show');

                    // Update
                    Route::put('/{id}', 'update')->name('update');
                    Route::patch('/{id}', 'update')->name('update');

                    // Activation
                    Route::post('/{id}/activation', 'activation')->name('activation')->middleware(['role:Root|Admin']);
                });
            });
        });
    });
});
