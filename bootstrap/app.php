<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/is-up',
    )
    ->withMiddleware(function(Middleware $middleware){
        // Alias middleware
        $middleware->alias([
            // Dummy (as in stupid) validation
            'dummy.validation'      => \App\Http\Middleware\DummyValidationMiddleware::class,

            // App-related
            'app.locale'            => \App\Http\Middleware\AppLocaleMiddleware::class,
            'app.nodebug'           => \App\Http\Middleware\DisableDebuggerMiddleware::class,
            
            // JWT-related
            'jwt.be'                => \App\Http\Middleware\JwtAuthBeMiddleware::class,
            'jwt.fe'                => \App\Http\Middleware\JwtAuthFeMiddleware::class,
            'jwt.global'            => \App\Http\Middleware\JwtAuthGlobalMiddleware::class,
            'jwt.guest'             => \App\Http\Middleware\JwtAuthGuestMiddleware::class,

            // Custom rate limiter
            'request.limiter'       => \App\Http\Middleware\RateLimitMiddleware::class,

            // Blade minify
            'minify.blade'          => \App\Http\Middleware\MinifyBladeMiddleware::class,

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
