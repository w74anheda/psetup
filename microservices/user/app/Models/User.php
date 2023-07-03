<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Casts\PersonalInfoCast;
use App\Models\Traits\HasAddress;
use App\Models\Traits\HasIp;
use App\Presenters\PresentAble;
use App\Presenters\User\Api as UserApiPresenter;
use App\Services\Acl\HasPermission;
use App\Services\Acl\HasRoles;
use App\Services\Auth\HasPhoneVerification;
use App\Services\Passport\CustomFindUserAndValidate as CustomFindUserAndValidateForPassport;
use App\Services\Passport\RevokeAble;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasPermission,
        HasRoles,
        PresentAble,
        CustomFindUserAndValidateForPassport,
        HasPhoneVerification,
        RevokeAble,
        HasAddress,
        HasIp;

    protected $presenterHandler = UserApiPresenter::class;

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
        'password',
    ];

    protected $hidden = [
        'registered_ip',
        'password',
    ];

    protected $casts = [
        'is_new'            => 'bool',
        'is_active'         => 'bool',
        'activated_at'      => 'datetime',
        'phone_verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'last_online_at'    => 'datetime',
        'password'          => 'hashed',
        'personal_info'     => PersonalInfoCast::class,
    ];

    const GENDERS = [ 'male', 'female', 'both' ];

    public function setLastOnlineAt(DateTime $dateTime = null)
    {
        $dateTime             = $dateTime ?? now();
        $this->last_online_at = $dateTime;
        $this->save();
    }

    public function isActive(): bool
    {
        return !!$this->is_active;
    }
    public function isNew(): bool
    {
        return !!$this->is_new;
    }
}
