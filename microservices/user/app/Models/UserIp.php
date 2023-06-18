<?php

namespace App\Models;

use App\Models\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIp extends Model
{
    use HasFactory, HasUser;

    protected $fillable = [
        'user_id',
        'ip'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model)
        {
            self::where('user_id', $model->user_id)->delete();
        });
    }



}
