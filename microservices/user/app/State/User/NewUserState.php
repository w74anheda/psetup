<?php

namespace App\State\User;

use App\DTO\UserCompleteRegisterDTO;
use App\Services\User\UserService;

class NewUserState extends UserState
{
    public function completeRegitration(UserCompleteRegisterDTO $dto): bool
    {
        return UserService::completeRegister($this->entity, $dto);
    }
}
