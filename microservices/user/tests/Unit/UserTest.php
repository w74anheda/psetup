<?php

namespace Tests\Unit;

use App\Console\Kernel;
use Illuminate\Foundation\Application;
use Tests\TestCase;

class UserTest extends TestCase
{
    // use RefreshDatabase;

    public function createApplication(): Application
    {
        $app = require __DIR__ . '/../../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    public function setup(): void
    {
        parent::setUp();
        // $this->artisan('migrate:fresh --seed');
    }

    protected function tearDown(): void
    {

    }





}
