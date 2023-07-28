<?php

namespace App\Models;

use App\Models\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIp extends Model
{
    use HasFactory, HasUser;

    protected $primaryKey = [ 'user_id', 'ip' ];

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'ip'
    ];

    //return $this->hasMany('relatedModels', ['foreignKey1', 'foreignKey2'], ['localKey1', 'localKey2']);


}
