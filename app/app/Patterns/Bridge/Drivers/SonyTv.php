<?php

namespace App\Patterns\Bridge\Drivers;

use App\Patterns\Bridge\Device;

class SonyTv implements Device
{
    private $model = 'sony';
    public function turnOn()
    {
        echo "$this->model turnOn";
    }

    public function turnOff()
    {
        echo "$this->model turnOn";
    }

    public function setChannel(int $channel)
    {
        echo "$this->model turnOn";
    }

}
