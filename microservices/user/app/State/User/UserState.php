<?php

namespace App\State\User;

use App\DTO\UserCompleteProfileDTO;
use App\DTO\UserCompleteRegisterDTO;
use App\State\Contracts\BaseState;

class UserState extends BaseState
{

    public function completeRegitration(UserCompleteRegisterDTO $dto): bool
    {
        return false;
    }

    public function completeProfile(UserCompleteProfileDTO $dto): bool
    {
        return false;
    }


}
