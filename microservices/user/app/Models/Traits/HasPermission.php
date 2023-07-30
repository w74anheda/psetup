<?php
namespace App\Models\Traits;



use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use DB;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;

trait HasPermission
{

    abstract public function permissions(): BelongsToMany;

    abstract public function hasPermission(string $permission_name): bool;

    private function getPermissions(string...$permissions_name)
    {
        return Permission::whereIn('name', $permissions_name)->get();
    }

    public function addPermissions(string...$permissions_name)
    {
        $permissions = $this->getPermissions(...$permissions_name);
        $this->permissions()->syncWithoutDetaching($permissions);
        return $this;
    }

    public function removePermissions(string...$permissions_name)
    {

        $permissions = $this->getPermissions(...$permissions_name);

        $this->permissions()->detach($permissions);

        return $this;
    }

    public function refreshPermissions(string...$permissions_name)
    {

        $permissions = $this->getPermissions(...$permissions_name);

        $this->permissions()->sync($permissions);

        return $this;
    }


}
