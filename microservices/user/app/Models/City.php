<?php

namespace App\Models;

use App\Models\Traits\HasAddress;
use App\Presenters\PresentAble;
use App\Presenters\Presenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Presenters\City\Api as CityApiPresenter;

class City extends Model
{
    use HasFactory, HasAddress, PresentAble;

    protected $fillable = [ 'state_id', 'name' ];

    public $timestamps = false;

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    protected function presenterHandler(): Presenter
    {
        return new CityApiPresenter($this);
    }

}
