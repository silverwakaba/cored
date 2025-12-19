<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider{
    /**
     * Register any application services.
     */
    public function register() : void{
        // Register 'bindings' as array
        $bindings = [
            // Core
            \App\Contracts\Core\ApiRepositoryInterface::class            => \App\Repositories\Core\ApiRepository::class,
            \App\Contracts\Core\CallToActionRepositoryInterface::class   => \App\Repositories\Core\EloquentCallToActionRepository::class,
            \App\Contracts\Core\MenuRepositoryInterface::class           => \App\Repositories\Core\EloquentMenuRepository::class,
            \App\Contracts\Core\PermissionRepositoryInterface::class     => \App\Repositories\Core\EloquentPermissionRepository::class,
            \App\Contracts\Core\RoleRepositoryInterface::class           => \App\Repositories\Core\EloquentRoleRepository::class,
            \App\Contracts\Core\UserRepositoryInterface::class           => \App\Repositories\Core\EloquentUserRepository::class,
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
    }
}
