<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider{
    /**
     * Register any application services.
     */
    public function register() : void{
        // Register 'bindings' as array
        $bindings = [
            // Core
            \App\Contracts\Core\ApiRepositoryInterface::class           => \App\Repositories\Core\ApiRepository::class,
            \App\Contracts\Core\BaseModuleRepositoryInterface::class    => \App\Repositories\Core\EloquentBaseModuleRepository::class,
            \App\Contracts\Core\BaseRequestRepositoryInterface::class   => \App\Repositories\Core\EloquentBaseRequestRepository::class,
            \App\Contracts\Core\CallToActionRepositoryInterface::class  => \App\Repositories\Core\EloquentCallToActionRepository::class,
            \App\Contracts\Core\MenuRepositoryInterface::class          => \App\Repositories\Core\EloquentMenuRepository::class,
            \App\Contracts\Core\NotificationRepositoryInterface::class  => \App\Repositories\Core\EloquentNotificationRepository::class,
            \App\Contracts\Core\PermissionRepositoryInterface::class    => \App\Repositories\Core\EloquentPermissionRepository::class,
            \App\Contracts\Core\RoleRepositoryInterface::class          => \App\Repositories\Core\EloquentRoleRepository::class,
            \App\Contracts\Core\UserRepositoryInterface::class          => \App\Repositories\Core\EloquentUserRepository::class,
            // ...add more core binding above ^^^

            // Project
            // ...add more project binding above ^^^
        ];
        
        // Register the 'bindings' using foreach
        foreach($bindings as $interface => $implementation){
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot() : void{
        // Prioritize core view path while keeping default fallback
        $this->app['config']->set('view.paths', [
            resource_path('views'),
            resource_path('views/core'),
            resource_path('views/project'),
        ]);

        // Load migrations from core and project directories
        // As Laravel doesn't automatically scan subdirectories, so we need to explicitly load them
        $this->loadMigrationsFrom([
            database_path('migrations/core'),
            database_path('migrations/project'),
        ]);

        // Configure Rate Limiters
        $this->configureRateLimiters();
    }

    /**
     * Configure rate limiters for API
     * 
     * Best Practice: Use named rate limiters with Laravel's throttle middleware
     * Format: ->middleware('throttle:limiter') or ->middleware('throttle:attempts,decayMinutes')
     */
    protected function configureRateLimiters() : void{
        // Rate limiter for general API endpoints
        RateLimiter::for('api', function (Request $request){
            // Authenticated: 120 requests/minute, Guests: 60 requests/minute
            return Limit::perMinute($request->user() ? 120 : 60)
                ->by($request->user() ? $request->user()->id : $request->ip())
                ->response(function (Request $request, array $headers){
                    return response()->json([
                        'success' => false,
                        'message' => 'You reached request limit. Please try again later.',
                    ], 429)->withHeaders($headers);
                });
        });

        // Rate limiter for authentication endpoints (stricter)
        RateLimiter::for('auth', function (Request $request){
            // 5 attempts per minute per IP
            return Limit::perMinute(5)
                ->by($request->ip())
                ->response(function (Request $request, array $headers){
                    return response()->json([
                        'success' => false,
                        'message' => 'Too many authentication attempts. Please try again later.',
                    ], 429)->withHeaders($headers);
                });
        });

        // Rate limiter for sensitive operations (RBAC, etc.)
        RateLimiter::for('sensitive', function (Request $request){
            // 30 requests per minute
            return Limit::perMinute(30)
                ->by($request->user() ? $request->user()->id : $request->ip())
                ->response(function (Request $request, array $headers){
                    return response()->json([
                        'success' => false,
                        'message' => 'Too many requests for this operation. Please slow down.',
                    ], 429)->withHeaders($headers);
                });
        });

        // Rate limiter for general/public endpoints
        RateLimiter::for('general', function (Request $request){
            // Authenticated: 100 requests/minute, Guests: 30 requests/minute
            return Limit::perMinute($request->user() ? 100 : 30)
                ->by($request->user() ? $request->user()->id : $request->ip())
                ->response(function (Request $request, array $headers){
                    return response()->json([
                        'success' => false,
                        'message' => 'You reached request limit. Please try again later.',
                    ], 429)->withHeaders($headers);
                });
        });
    }
}
