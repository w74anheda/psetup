<?php

namespace App\State\User;

use App\DTO\UserCompleteRegisterDTO;
use App\State\Contracts\BaseState;

class UserState extends BaseState
{

    public function completeRegitration(UserCompleteRegisterDTO $dto): bool
    {
        return false;
    }


}
