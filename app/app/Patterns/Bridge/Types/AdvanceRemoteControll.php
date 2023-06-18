<?php

namespace App\Patterns\Bridge\Types;

use App\Patterns\Bridge\RemoteControll;

class AdvanceRemoteControll extends RemoteControll
{
    public function setChannel(int $channel)
    {
        return $this->device->setChannel($channel);
    }
}
