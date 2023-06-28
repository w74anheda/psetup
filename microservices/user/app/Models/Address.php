<?php

namespace App\Models;

use App\Models\Traits\HasCity;
use App\Models\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use
        HasFactory,
        HasUser,
        HasCity;

    protected $fillable = [
        'user_id',
        'city_id',
        'full_address',
        'house_number',
        'unit_number',
        'postalcode',
        'latitude',
        'longitude',
    ];

    public $timestamps = false;

}
