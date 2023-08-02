<?php

namespace App\Presenters\City;

use App\Presenters\Presenter as ModelPresenter;

class Api extends ModelPresenter
{

    public function customAttribute()
    {
        return $this->model + 'some text';
    }
}
