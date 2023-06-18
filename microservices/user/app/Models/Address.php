<?php

namespace App\Models;

use App\Models\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use
        HasFactory,
        HasUser;

    protected $fillable = [
        'user_id',
        'city_id',
        'full_address',
        'house_number',
        'unit_number',
        'postalcode',
        'point',
    ];

    protected $timestamps = false;

    public function city()
    {
        return $this->belongsTo(City::class);
    }

}
