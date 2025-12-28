<?php

namespace App\Providers;

use App\Models\User\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use App\Models\User\Role;

class AclServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
       //
    }

    /**
     * Bootstrap services.
     */
    public function boot(Request $request): void
    {
        Gate::before(function (User $user){
            return $user->hasRole('super-admin');
        });

        Role::get()->map(function ($role){
            Gate::define($role->name, function (User $user, ?string $permission = null) use ($role){
                if (!empty($permission))
                    return (bool) $user->hasPermissionInRole($role->name,$permission);
                else
                    return $user->hasRole($role->name);
            });
        });
    }
}

