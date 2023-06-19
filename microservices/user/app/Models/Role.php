<?php

namespace App\Models;

use App\Services\Acl\HasPermission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory, HasPermission;

    public $timestamps = false;
    protected $fillable = [
        'name',
    ];
}
