<?php


namespace App\Services\Acl;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Arr;

trait HasRoles
{

    public function roles()
    {
        switch ($this) {

            case $this instanceof User:
                return $this->belongsToMany(Role::class, 'users_roles');
            case $this instanceof Permission:
                return $this->belongsToMany(Role::class, 'roles_permissions');
        }
    }

    protected function getAllRoles(string ...$roles_name)
    {
        return Role::whereIn('name', Arr::flatten($roles_name))->get();
    }

    public function addRoles(string ...$roles_name)
    {

        $roles = $this->getAllRoles(...$roles_name);

        $this->roles()->syncWithoutDetaching($roles);

        return $this;
    }

    public function removeRoles(string ...$roles_name)
    {

        $roles = $this->getAllRoles(...$roles_name);

        $this->roles()->detach($roles);

        return $this;
    }

    public function refreshRoles(string ...$roles_name)
    {

        $roles = $this->getAllRoles(...$roles_name);

        $this->roles()->sync($roles);

        return $this;
    }

    public function hasRole(string $role_name): bool
    {
        return $this->roles->contains('name', $role_name);
    }
}
