<?php

namespace App\Presenters\User;

use App\Presenters\Presenter as ModelPresenter;

class Api extends ModelPresenter
{

    public function full_name()
    {
        return "{$this->model->first_name} {$this->model->last_name}";
    }
}
