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

    protected function hasPermission(string $permission): bool
    {
        return (bool) $this->permissions()->where('name',$permission)->count();
    }
    protected function hasPermissionInRole(string $permission): bool
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
    public function hasRoleOrPermission (string $roleOrPermission): bool
    {
        if ($this->hasPermissionInRole($roleOrPermission) || $this->hasPermission($roleOrPermission)){
            return true;
        }
        return false;
    }
}
