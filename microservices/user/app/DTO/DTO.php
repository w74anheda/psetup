<?php

namespace App\DTO;

use InvalidArgumentException;
use ReflectionObject;
use ReflectionProperty;

class DTO
{

    public function validate(array $required = null)
    {
        $required = $required ?? $this->props();

        foreach( $required as $prop )
        {
            if(!isset($this->{$prop}))
                throw new InvalidArgumentException("{$prop} not filled");
        }
    }

    private function props(): array
    {
        return array_map(
            fn($props) => $props->name,
            (new ReflectionObject($this))
                ->getProperties(ReflectionProperty::IS_PROTECTED)
        );
    }

    public function __get($name)
    {
        if(in_array($name, $this->props()))
            return $this->{$name};
    }


}
