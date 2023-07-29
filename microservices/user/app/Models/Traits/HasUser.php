<?php

namespace App\Models\Traits;

use App\Models\User;

trait HasUser
{
    public function users()
    {
        return $this->belongsToMany(User::class, 'users_permissions');
    }
}
