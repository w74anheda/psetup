<?php

namespace App\DTO;

class UserCompleteProfileDTO extends DTO
{
    protected $birth_day;
    protected $national_id;

    public function setBirthDay(string $birth_day)
    {
        $this->birth_day = $birth_day;
        return $this;
    }

    public function setNationalId(string $national_id)
    {
        $this->national_id = $national_id;
        return $this;
    }
}
