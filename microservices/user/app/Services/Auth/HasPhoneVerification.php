<?php

namespace App\Services\Auth;

use App\Models\UserPhoneVerification;

trait HasPhoneVerification
{
    public function phoneVerifications()
    {
        return $this->hasOne(UserPhoneVerification::class);
    }

}
