<?php
namespace App\Models\Traits;



use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;

trait HasRoles
{

    abstract public function roles(): BelongsToMany;


    protected function getRoles(string...$roles_name)
    {
        return Role::whereIn('name', Arr::flatten($roles_name))->get();
    }

    public function addRoles(string...$roles_name): self
    {
        $roles = $this->getRoles(...$roles_name);
        $this->roles()->syncWithoutDetaching($roles);
        return $this;
    }

    public function removeRoles(string...$roles_name): self
    {

        $roles = $this->getRoles(...$roles_name);

        $this->roles()->detach($roles);

        return $this;
    }

    public function refreshRoles(string...$roles_name): self
    {

        $roles = $this->getRoles(...$roles_name);
        $this->roles()->sync($roles);

        return $this;
    }

    public function hasRole(string $role_name): bool
    {
        return $this->roles->contains('name', $role_name);
    }
}
