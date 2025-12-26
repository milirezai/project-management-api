<?php

namespace App\Providers;

use App\Models\User\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use App\Models\User\Permission;

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
        Permission::get()->map(function ($permission){
            Gate::define($permission->name, function (User $user) use ($permission){
                return (bool) $user->hasPermission($permission->name);
            });
        });
    }
}
