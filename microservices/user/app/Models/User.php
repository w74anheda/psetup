<?php

namespace App\Models;

use App\Casts\PersonalInfoCast;
use App\Models\Traits\HasAddress;
use App\Models\Traits\HasIp;
use App\Models\Traits\HasPermission;
use App\Models\Traits\HasPhoneVerification;
use App\Models\Traits\HasRoles;
use App\Presenters\PresentAble;
use App\Presenters\Presenter;
use App\Presenters\User\Api as UserApiPresenter;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasPermission,
        HasRoles,
        PresentAble,
        HasPhoneVerification,
        HasAddress,
        HasIp;

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function booted()
    {
        parent::boot();
        static::creating(function ($user)
        {
            $user->{$user->getKeyName()} = Str::uuid();
        });

    }

    protected function presenterHandler(): Presenter
    {
        return new UserApiPresenter($this);
    }

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'phone',
        'activated_at',
        'last_online_at',
        'email',
        'email_verified_at',
        'registered_ip',
        'is_active',
        'is_new',
        'personal_info',
    ];

    protected $hidden = [
        'registered_ip',
        'password',
    ];

    protected $casts = [
        'is_new'            => 'bool',
        'is_active'         => 'bool',
        'email_verified_at' => 'datetime',
        'activated_at'      => 'datetime',
        'last_online_at'    => 'datetime',
        'password'          => 'hashed',
        'personal_info'     => PersonalInfoCast::class,
    ];

    const GENDERS = [ 'male', 'female', 'both' ];

    public function isActive(): bool
    {
        return !!$this->is_active;
    }
    public function isNew(): bool
    {
        return !!$this->is_new;
    }

    public function hasPermission(string $permission_name): bool
    {
        $permission = Permission::where('name', $permission_name)->first();
        if(!$permission) return false;
        return $this->hasPermissionThroughRole($permission)
            || $this->permissions->contains('name', $permission_name);
    }

    protected function hasPermissionThroughRole(Permission $permission): bool
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

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'users_permissions');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'users_roles');
    }

    public function allPermissions()
    {
        if(!$this instanceof User) return null;

        $a = DB::table('users')
            ->select('permissions.id as id', 'permissions.name as name')
            ->join('users_permissions', 'users.id', '=', 'users_permissions.user_id')
            ->join('permissions', 'permissions.id', '=', 'users_permissions.user_id')
            ->where([ 'user_id' => $this->id ])
            ->distinct();

        $b = DB::table('users')
            ->select('permissions.id as id', 'permissions.name as name')
            ->join('users_roles', 'users.id', '=', 'users_roles.user_id')
            ->join('roles', 'users_roles.role_id', '=', 'roles.id')
            ->join('roles_permissions', 'roles.id', '=', 'roles_permissions.role_id')
            ->join('permissions', 'permissions.id', '=', 'roles_permissions.permission_id')
            ->where([ 'user_id' => $this->id ])
            ->distinct();

        return $a->union($b)->orderBy('id');
    }

}
