<?php

namespace App\Patterns\Bridge\Types;

use App\Patterns\Bridge\RemoteControll;

class MovieRemoteControll extends RemoteControll
{
    public function setChannel(int $channel)
    {
        return $this->device->setChannel($channel);
    }

    public function play()
    {
        return $this->device->play();
    }

    public function pause()
    {
        return $this->device->pause();
    }
}
