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

    protected function checkPermission(string $permission): bool
    {
        return (bool) $this->permissions()->where('name',$permission)->count();
    }
    protected function checkPermissionInRole(string $permission): bool
    {
        foreach ($this->roles as  $role){
           $hasPermission = $role->permissions()->where('name',$permission)->count();
          if ($hasPermission)
              return true;
        }
        return false;
    }

    public function hasRole(string $roleName): bool
    {
         foreach ($this->roles as $role){
             if ($role->name === $roleName)
                 return true;
         }
         return false;
    }
    public function hasPermission(string $permission): bool
    {
        if ($this->checkPermissionInRole($permission) || $this->checkPermission($permission)){
            return true;
        }
        return false;
    }
}
