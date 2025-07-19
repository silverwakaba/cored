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
            \App\Contracts\ApiRepositoryInterface::class   => \App\Repositories\ApiRepository::class,
            \App\Contracts\UserRepositoryInterface::class   => \App\Repositories\EloquentUserRepository::class,
            \App\Contracts\RoleRepositoryInterface::class   => \App\Repositories\EloquentRoleRepository::class,
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
        //
    }
}
