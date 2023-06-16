<?php

namespace App\Presenters\User;

use App\Presenters\Presenter as ModelPresenter;

class Api2 extends ModelPresenter
{

    public function customAttribute()
    {
        return $this->model + 'some text';
    }
}
