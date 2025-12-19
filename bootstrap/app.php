<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__.'/../routes/core/web.php',          // core (boilerplate)
            __DIR__.'/../routes/project/web.php',       // feature overrides/extensions
        ],
        api: [
            __DIR__.'/../routes/core/api.php',          // core (boilerplate)
            __DIR__.'/../routes/project/api.php',       // feature overrides/extensions
        ],
        commands: [
            __DIR__.'/../routes/core/console.php',      // core (boilerplate)
            __DIR__.'/../routes/project/console.php',   // feature overrides/extensions
        ],
        channels: [
            __DIR__.'/../routes/core/channels.php',     // core (boilerplate)
            __DIR__.'/../routes/project/channels.php',  // feature overrides/extensions
        ],
        health: '/watsup',
    )
    ->withMiddleware(function(Middleware $middleware){
        // Alias middleware
        $middleware->alias([
            // Dummy (as in stupid) validation
            'dummy.validation'      => \App\Http\Middleware\Core\DummyValidationMiddleware::class,

            // App-related
            'app.locale'            => \App\Http\Middleware\Core\AppLocaleMiddleware::class,
            'app.nodebug'           => \App\Http\Middleware\Core\DisableDebuggerMiddleware::class,
            
            // JWT-related
            'jwt.be'                => \App\Http\Middleware\Core\JwtAuthBeMiddleware::class,
            'jwt.fe'                => \App\Http\Middleware\Core\JwtAuthFeMiddleware::class,
            'jwt.global'            => \App\Http\Middleware\Core\JwtAuthGlobalMiddleware::class,
            'jwt.guest'             => \App\Http\Middleware\Core\JwtAuthGuestMiddleware::class,

            // Custom rate limiter
            'request.limiter'       => \App\Http\Middleware\Core\RateLimitMiddleware::class,

            // Blade minify
            'minify.blade'          => \App\Http\Middleware\Core\MinifyBladeMiddleware::class,

            // Spatie role-permission related
            'role'                  => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'            => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission'    => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        // Route exception without CSRF token validation
        $middleware->validateCsrfTokens(except: [
            // Logout
            'auth/logout',
        ]);
    })
    ->withExceptions(function(Exceptions $exceptions){
        // JWT exception
        // See: \app\Http\Middleware\JwtAuthBeMiddleware.php

        // Spatie unauthorized exception response, only applied to the entire api route
        if(request()->is('api/*')){
            // This one is for Spatie
            $exceptions->render(function(\Spatie\Permission\Exceptions\UnauthorizedException $e, $request){
                return response()->json([
                    'success'   => false,
                    'message'   => 'Forbidden access.',
                ], 403);
            });
        }
    })->create();
