<?php

namespace App\Models;

use App\Models\Traits\HasCity;
use App\Models\Traits\BelongsToUser;
use App\Presenters\Address\Api as AddressApiPresenter;
use App\Presenters\PresentAble;
use App\Presenters\Presenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use
        HasFactory,
        BelongsToUser,
        HasCity,
        PresentAble;

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

    protected function presenterHandler(): Presenter
    {
        return new AddressApiPresenter($this);
    }

}
