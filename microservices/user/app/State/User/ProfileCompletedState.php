<?php

namespace App\State\User;

use App\DTO\UserCompleteProfileDTO;
use App\DTO\UserCompleteRegisterDTO;
use App\Services\User\UserService;

class ProfileCompletedState extends UserState
{

    public function completeProfile(UserCompleteProfileDTO $dto): bool
    {
        return UserService::profileComplete($this->entity, $dto);
    }

}
