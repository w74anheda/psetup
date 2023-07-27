<?php
namespace App\Models\Traits;



use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use DB;
use Illuminate\Support\Arr;

trait HasPermission
{

    public function permissions()
    {
        switch($this)
        {
            case $this instanceof User:
                return $this->belongsToMany(Permission::class, 'users_permissions');
            case $this instanceof Role:
                return $this->belongsToMany(Permission::class, 'roles_permissions');
        }
    }

    public function allPermissions()
    {
        if(!$this instanceof User) return null;

        $a = DB::table('users')
        ->select('permissions.id as id', 'permissions.name as name')
        ->join('users_permissions','users.id','=','users_permissions.user_id')
        ->join('permissions','permissions.id','=','users_permissions.user_id')
        ->where(['user_id' => $this->id])
        ->distinct();

        $b = DB::table('users')
        ->select('permissions.id as id', 'permissions.name as name')
        ->join('users_roles','users.id','=','users_roles.user_id')
        ->join('roles','users_roles.role_id','=','roles.id')
        ->join('roles_permissions','roles.id','=','roles_permissions.role_id')
        ->join('permissions','permissions.id','=','roles_permissions.permission_id')
        ->where(['user_id' => $this->id])
        ->distinct();

        return $a->union($b)->orderBy('id');
    }

    private function getAllPermissions(string...$permissions_name)
    {
        return Permission::whereIn('name', $permissions_name)->get();
    }

    public function addPermissions(string...$permissions_name)
    {
        $permissions = $this->getAllPermissions(...$permissions_name);
        $this->permissions()->syncWithoutDetaching($permissions);
        return $this;
    }

    public function removePermissions(string...$permissions_name)
    {

        $permissions = $this->getAllPermissions(...$permissions_name);

        $this->permissions()->detach($permissions);

        return $this;
    }

    public function refreshPermissions(string...$permissions_name)
    {

        $permissions = $this->getAllPermissions(...$permissions_name);

        $this->permissions()->sync($permissions);

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
