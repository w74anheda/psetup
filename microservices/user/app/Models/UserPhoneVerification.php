<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPhoneVerification extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model)
        {
            self::where('user_id', $model->user_id)->delete();
        });
    }

    protected $fillable = [
        'user_id',
        'code',
        'expire_at',
        'hash',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
