<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIp extends Model
{
    use HasFactory;

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
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
