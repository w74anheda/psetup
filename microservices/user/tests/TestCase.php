<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\Assert;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;


    public function setup(): void
    {
        $this->withExceptionHandling();
        parent::setUp();
        $this->artisan('passport:install --force');
        // $this->artisan('passport:keys --force');
    }

    public function assertCompositeKeyModelExists(Model $model)
    {
        $query = $model::query();

        foreach( (array) $model->getKeyName() as $key )
            $query->where($key, $model->{$key});
        $model = $query->first();

        Assert::assertNotNull(
            $model,
            "The model {$model->getTable()} with composite key values does not exist."
        );
    }
}
