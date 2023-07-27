<?php

namespace App\Models;

use App\Models\Traits\HasAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory, HasAddress;

    protected $fillable = [ 'state_id', 'name' ];

    public $timestamps = false;

    public function state()
    {
        return $this->belongsTo(State::class);
    }

}
