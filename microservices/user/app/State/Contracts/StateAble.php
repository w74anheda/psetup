<?php

namespace App\State\Contracts;

trait StateAble
{

    abstract public function state(): BaseState;

}
