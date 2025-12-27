<?php

namespace App\Trait;

use App\Models\User\Permission;
use App\Models\User\Role;

trait HasAcl
{
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
    
    public function hasRole(string $roleName): bool
    {
         foreach ($this->roles as $role){
             if ($role->name === $roleName)
                 return true;
         }
         return false;
    }

    public function hasPermissionInRole(string $role, string $permission)
    {
        $role = $this->roles()->where('name','=',$role)->first();
        $permission = (bool) $role->permissions()->where('name','=',$permission)->count();
        if ($permission)
            return true;
        else
            return false;
    }
}

