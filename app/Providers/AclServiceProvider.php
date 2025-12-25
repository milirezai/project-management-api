<?php

namespace App\Providers;

use App\Models\User\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
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
            return $user->hasRole('admin');
        });
//
//        Permission::get()->map(function ($permission){
//            Gate::define($permission->name,function (User $user) use ($permission){
//                return $user->hasRoleOrPermission($permission->name);
//            });
//        });

    }
}
