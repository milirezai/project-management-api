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

    public function hasRole(string $role)
    {
        return  (bool) $this->roles()->where('name','=',$role)->count();
    }

    public function hasRoleWithPermission(string $roleName, ?string $permission = null): bool
    {
        return $this->hasRole($roleName)
            and $this->hasPermissionInRole($roleName, $permission)
            ? true : false;
    }

    public function hasPermissionInRole(string $role, string $permission)
    {
        $role = $this->roles()->where('name','=',$role)->first();
        if (!empty($role)){
            $permission = (bool) $role->permissions()->where('name','=',$permission)->count();
            if ($permission)
                return true;
            else
                return false;
        }
            return false;
    }

    public function hasPermission(string $permission)
    {
       foreach ($this->roles as $role){
           $hasPermission = (bool) $role->permissions()->where('name','=',$permission)->count();
           if ($hasPermission)
               return $hasPermission;
       }
       return false;
    }
}
