<?php

namespace App\Patterns\Bridge;

class RemoteControll
{
    public function __construct(protected Device $device)
    {
    }


    public function turnOn()
    {
        return $this->device->turnOn();
    }

    public function turnOff()
    {
        return $this->device->turnOff();
    }
}
