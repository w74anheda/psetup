<?php


namespace App\Services\Acl;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Arr;

trait HasPermission
{

    public function Permissions()
    {
        switch($this)
        {

            case $this instanceof User:
                return $this->belongsToMany(Permission::class, 'users_permissions');
            case $this instanceof Role:
                return $this->belongsToMany(Permission::class, 'roles_permissions');
        }
    }


    private function getAllPermissions(string...$permissions_name)
    {
        return Permission::whereIn('name', $permissions_name)->get();
    }


    public function addPermissions(string...$permissions_name)
    {
        $permissions = $this->getAllPermissions(...$permissions_name);
        $this->Permissions()->syncWithoutDetaching($permissions);

        return $this;
    }


    public function removePermissions(string...$permissions_name)
    {

        $permissions = $this->getAllPermissions(...$permissions_name);

        $this->Permissions()->detach($permissions);

        return $this;
    }


    public function refreshPermissions(string...$permissions_name)
    {

        $permissions = $this->getAllPermissions(...$permissions_name);

        $this->Permissions()->sync($permissions);

        return $this;
    }


    public function hasPermission(string $permission_name): bool
    {
        $permission = Permission::where('name', $permission_name)->first();
        if(!$permission) return false;
        return $this->hasPermissionThroughRole($permission)
            || $this->permissions->contains('name', $permission_name);
    }


    public function hasPermissionThroughRole(Permission $permission)
    {

        foreach( $permission->roles as $role )
        {
            if($this->roles->contains($role))
            {
                return true;
            }
        }

        return false;
    }
}
