<?php

namespace App\Models;

use App\Models\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPhoneVerification extends Model
{
    use HasFactory, HasUser;

    protected $primaryKey = ['code', 'hash'];
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'code',
        'expire_at',
        'hash',
    ];

    public $timestamps = false;




}
