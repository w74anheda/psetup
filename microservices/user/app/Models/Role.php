<?php

namespace App\Models;

use App\Models\Traits\HasPermission;
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
