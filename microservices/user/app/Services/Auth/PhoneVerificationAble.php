<?php

namespace App\Services\Auth;

use Illuminate\Support\Str;

trait PhoneVerificationAble
{

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

}
