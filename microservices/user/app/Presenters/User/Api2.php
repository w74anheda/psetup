<?php

namespace App\Presenters\User;

use App\Presenters\Presenter as ModelPresenter;

class Api2 extends ModelPresenter
{

    public function full_name()
    {
        return "222{$this->model->first_name} {$this->model->last_name}";
    }
}
