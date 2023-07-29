<?php

namespace App\Models;

use App\Models\Traits\HasRoles;
use App\Models\Traits\BelongsToManyUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory,
        HasRoles,
        BelongsToManyUser;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

}
