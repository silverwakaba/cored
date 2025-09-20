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
Route::prefix('/')->name('fe.')->middleware([
    'jwt.global', 'minify.blade',
])->group(function(){
    // Page without much logic
    Route::prefix('base')->name('base.')->controller(BasedataController::class)->group(function(){
        // Menu
        Route::get('menu', 'menu')->name('menu');
    });

    // Page without any logic | Separate controller for flexibility
    Route::prefix('/')->name('page.')->group(function(){
        // Index
        Route::get('/', [PageController::class, 'index'])->name('index');
    });

    // General Auth
    Route::prefix('auth')->name('auth.')->middleware([
        'jwt.guest',
    ])->controller(GeneralAuthController::class)->group(function(){
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
    Route::prefix('apps')->name('apps.')->middleware([
        'jwt.fe',
    ])->group(function(){
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

// // Route Group
// Route::group(['prefix' => '/', 'middleware' => ['app.locale', 'jwt.global']], function(){
//     // Index
//     Route::get('/', [PageController::class, 'index'])->name('fe.index');

//     // Authentication
//     Route::group(['prefix' => 'auth', 'middleware' => ['jwt.guest']], function(){
//         // Register
//         Route::get('register', [AuthController::class, 'register'])->name('fe.auth.register');
//         Route::post('register', [AuthController::class, 'registerPost'])->middleware(['throttle:10,1']);

//         // Login
//         Route::get('login', [AuthController::class, 'login'])->name('fe.auth.login');
//         Route::post('login', [AuthController::class, 'loginPost'])->middleware(['throttle:5,1']);

//         // Verify
//         Route::get('login/{id}', [AuthController::class, 'verify'])->name('fe.auth.verify')->middleware(['signed']);

//         // Lost password
//         Route::get('lost-password', [AuthController::class, 'lostPassword'])->name('fe.auth.lost-password');
//         Route::post('lost-password', [AuthController::class, 'lostPasswordPost'])->middleware(['throttle:10,1']);

//         // Reset password
//         Route::get('lost-password/{id}', [AuthController::class, 'resetPassword'])->name('fe.auth.reset-password');
//         Route::post('lost-password/{id}', [AuthController::class, 'resetPasswordPost']);

//         // Logout
//         Route::post('logout', [AuthController::class, 'logout'])->name('fe.auth.logout')->middleware(['jwt.fe'])->withoutMiddleware(['jwt.guest']);
//     });

//     // Apps
//     Route::group(['prefix' => 'apps', 'middleware' => ['jwt.fe']], function(){
//         // Index of Apps
//         Route::get('/', [AppsController::class, 'index'])->name('fe.apps.index');

//         // Management
//         Route::group(['prefix' => 'management', 'middleware' => ['role:Admin']], function(){
//             // Index of Management
//             Route::get('/', [AppsController::class, 'management'])->name('fe.apps.management.index');

//             // Item Test
//             Route::group(['prefix' => 'item'], function(){
//                 // Datatable
//                 Route::get('/', [ItemManagementController::class, 'index'])->name('fe.apps.management.item.index');

//                 // Upsert
//                 Route::post('upsert', [ItemManagementController::class, 'upsert'])->name('fe.apps.management.item.upsert');

//                 // Read
//                 Route::get('read/{id}', [ItemManagementController::class, 'read'])->name('fe.apps.management.item.read');

//                 // Delete
//                 Route::post('delete/{id}', [ItemManagementController::class, 'delete'])->name('fe.apps.management.item.delete');
//             });

//             // User Management
//             Route::group(['prefix' => 'user'], function(){
//                 // Index of User List
//                 Route::get('/', [UserManagementController::class, 'index'])->name('fe.apps.management.user.index');

//                 // Index of User Management
//                 Route::get('{id}', [UserManagementController::class, 'management'])->name('fe.apps.management.user.manage.index');

//                 // Avatar
//                 Route::get('avatar/{id}', [UserManagementController::class, 'avatar'])->name('fe.apps.management.user.manage.avatar');
//                 Route::post('avatar/{id}', [UserManagementController::class, 'avatarPost']);
//             });
//         });
//     });

//     // System
//     Route::group(['prefix' => 'sys'], function(){
//         // Dark mode
//         Route::get('switch-color', [SystemController::class, 'color'])->name('fe.sys.color');

//         // Locale
//         Route::get('locale-{lang}', [SystemController::class, 'locale'])->name('fe.sys.locale');
//     });
// });
