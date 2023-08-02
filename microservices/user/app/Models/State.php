<?php

namespace App\Models;

use App\Models\Traits\HasCities;
use App\Presenters\PresentAble;
use App\Presenters\Presenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Presenters\State\Api as StateApiPresenter;

class State extends Model
{
    use HasFactory, HasCities,PresentAble;

    protected $fillable = [ 'name' ];

    public $timestamps = false;


    protected function presenterHandler(): Presenter
    {
        return new StateApiPresenter($this);
    }

}
