<?php
namespace App\Models\Traits;


use App\Models\UserPhoneVerification;

trait HasPhoneVerification
{
    public function phoneVerifications()
    {
        return $this->hasOne(UserPhoneVerification::class);
    }

}
