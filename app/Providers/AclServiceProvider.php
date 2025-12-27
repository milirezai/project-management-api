<?php

namespace App\Providers;

use App\Models\User\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use App\Models\User\Permission;
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
//        Permission::get()->map(function ($permission){
//            Gate::define($permission->name, function (User $user) use ($permission){
//                return (bool) $user->hasPermission($permission->name);
//            });
//        });

        Role::get()->map(function ($role){
            Gate::define($role->name, function (User $user) use ($role){
                return (bool) $user->hasRole($role->name);
            });
        });
    }
}

