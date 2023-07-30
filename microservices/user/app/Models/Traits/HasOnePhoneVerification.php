<?php
namespace App\Models\Traits;


use App\Models\UserPhoneVerification;

trait HasOnePhoneVerification
{
    public function phoneVerifications()
    {
        return $this->hasOne(UserPhoneVerification::class);
    }

}
