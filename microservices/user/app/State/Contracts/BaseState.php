<?php

namespace App\State\Contracts;

abstract class BaseState
{

    public function __construct(protected $entity)
    {
    }



}
