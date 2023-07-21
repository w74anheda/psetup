<?php
namespace App\Services\Http;


use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;

class CustomHttp
{
    use MakesHttpRequests;
    protected $app;

    public function __construct()
    {
        $this->app = app();
    }

}
