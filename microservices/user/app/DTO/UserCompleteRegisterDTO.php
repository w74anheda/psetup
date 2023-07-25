<?php

namespace App\DTO;

class UserCompleteRegisterDTO extends DTO
{
    protected $first_name;
    protected $last_name;
    protected $gender;

    public function setFirstName(string $first_name)
    {
        $this->first_name = $first_name;
        return $this;
    }

    public function setLastName(string $last_name)
    {
        $this->last_name = $last_name;
        return $this;
    }

    public function setGender(string $gender)
    {
        if(in_array($gender, [ 'male', 'female', 'both' ]))
            $this->gender = $gender;
        return $this;
    }


}
