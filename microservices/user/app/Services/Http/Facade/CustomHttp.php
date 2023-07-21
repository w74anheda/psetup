<?php
namespace App\Services\Http\Facade;

use Illuminate\Support\Facades\Facade;

class CustomHttp  extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'CustomHttp';
    }
}
