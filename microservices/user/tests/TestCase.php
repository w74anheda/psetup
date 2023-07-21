<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    // use RefreshDatabase;


    public function setup(): void
    {
        $this->withExceptionHandling();
        parent::setUp();
        // $this->artisan('passport:install --force');
        // $this->artisan('passport:keys --force');
    }
}
