<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
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
    public function boot(): void
    {
        auth()->loginUsingId(1);

        Gate::before(function (User $user){
            return $user->hasRole('admin');
        });

        Permission::get()->map(function ($permission){
            Gate::define($permission->name,function (User $user) use ($permission){
                return $user->hasRoleOrPermission($permission->name);
            });
        });


    }
}
