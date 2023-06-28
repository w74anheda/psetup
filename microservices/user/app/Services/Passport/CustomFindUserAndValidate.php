<?php

namespace App\Services\Passport;

use App\Models\User;

trait CustomFindUserAndValidate
{

    public function findForPassport(string $phone): User
    {
        return $this->where('phone', $phone)->first();
    }

    public function validateForPassportPasswordGrant(string $code): bool
    {
        return !!$this->phoneVerifications()->where('code', $code)->first();
    }

}
