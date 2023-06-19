<?php

namespace App\Models;

use App\Models\Traits\HasCities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory, HasCities;

    protected $fillable = [ 'name' ];

    public $timestamps = false;


}
