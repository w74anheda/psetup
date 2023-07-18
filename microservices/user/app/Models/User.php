<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Casts\PersonalInfoCast;
use App\Models\Traits\HasAddress;
use App\Models\Traits\HasIp;
use App\Models\Traits\HasPhoneVerification;
use App\Presenters\PresentAble;
use App\Presenters\Presenter;
use App\Presenters\User\Api as UserApiPresenter;
use App\Services\Acl\HasPermission;
use App\Services\Acl\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        static::creating(function ($user)
        {
            $user->id = Str::uuid();
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

}
