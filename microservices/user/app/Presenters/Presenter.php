<?php
namespace App\Presenters;

use Illuminate\Database\Eloquent\Model;

abstract class Presenter
{

    public function __construct(protected Model $model)
    {
    }

    public function __get($property)
    {

        if(method_exists($this, $property))
        {
            return $this->{$property}();
        }

        return $this->model->{$property};
    }

    public function __call($name, $arguments)
    {
        if(method_exists($this, $name))
        {
            return $this->{$name}(...$arguments);
        }

        return $this->model->{$name}(...$arguments);
    }
}
