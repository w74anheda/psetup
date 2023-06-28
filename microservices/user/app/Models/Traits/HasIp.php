<?php

namespace App\Models\Traits;

use App\Models\UserIp;

trait HasIp
{
    public function ips()
    {
        return $this->hasMany(UserIp::class);
    }
}
