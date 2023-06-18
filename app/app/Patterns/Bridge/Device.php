<?php

namespace App\Patterns\Bridge;

interface Device
{
    public function turnOn();

    public function turnOff();

    public function setChannel(int $channel);

}
