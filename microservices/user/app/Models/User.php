<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Casts\PersonalInfoCast;
use App\Services\Acl\HasPermission;
use App\Services\Acl\HasRoles;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasPermission, HasRoles;

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
        'personal_info',
        'password',
    ];

    protected $hidden = [
        'registered_ip',
        'password',
    ];

    protected $casts = [
        'is_active'         => 'bool',
        'phone_verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'password'          => 'hashed',
        'personal_info'     => PersonalInfoCast::class,
    ];

    const GENDERS = [ 'male', 'female', 'both' ];

    public function revoke()
    {
        $refreshTokenRepository = resolve(RefreshTokenRepository::class);
        $this->tokens->each(
            fn($token) => $token->revoke() &&
            $token->delete() &&
            $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id)
        );
    }

    public function generateVerificationCode(string $code = null)
    {
        $code = $code ??
            generate_random_digits_with_specefic_length(
                app('PHONE_VERIFICATION_CODE_LENGTH')
            );

        return $this->phoneVerifications()->create([
            'code'      => $code,
            'expire_at' => now()->addSeconds(
                app('PHONE_VERIFICATION_CODE_LIFETIME_SECONDS')
            ),
            'hash'      => Str::uuid()
        ]);
    }

    public function clearVerificationCode(string $hash)
    {
        $this->phoneVerifications()->where('hash', $hash)->delete();
    }

    public function phoneVerifications()
    {
        return $this->hasOne(UserPhoneVerification::class);
    }

    public function findForPassport(string $phone): User
    {
        return $this->where('phone', $phone)->first();
    }

    public function validateForPassportPasswordGrant(string $code): bool
    {
        return !!$this->phoneVerifications()->where('code', $code)->first();
    }

    public function setLastOnlineAt(DateTime $dateTime = null)
    {
        $dateTime            = $dateTime ?? now();
        $this->last_online_at = $dateTime;
        $this->save();
    }

}
