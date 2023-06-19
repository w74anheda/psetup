<?php

namespace App\Models;

use App\Models\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory, HasRoles;
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];


}
