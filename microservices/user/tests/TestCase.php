<?php

namespace Tests;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Response;
use Illuminate\Testing\Assert;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    // vendor/bin/phpunit  --coverage-html coverage_report

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

    public function assertIsDate(string $date)
    {
        $isDate = date('Y-m-d', strtotime($date)) == $date;
        Assert::assertTrue(
            !!$isDate,
            "invalid date format"
        );
    }

    public function assertIsDateTime(string $date)
    {
        $isDate = date('Y-m-d H:i:s', strtotime($date)) == $date;
        Assert::assertTrue(
            !!$isDate,
            "invalid date time format"
        );
    }

    protected function createPassportToken(User $user = null)
    {
        $user     = $user ?? User::factory()->create();
        $response = $this->post(
            route('auth.login.phonenumber.request'),
            [ 'phone' => $user->phone ]
        );

        $data     = $response->decodeResponseJson()->json();
        $response = $this->postJson(
            route('auth.login.phonenumber.verify'),
            [
                'code' => $data['verification']['code'],
                'hash' => $data['verification']['hash'],
            ]
        )->json();
        return $response;
    }

    protected function requestWithToken(User $user = null)
    {
        $token = $this->createPassportToken($user)['access_token'];
        $user  = $user ?? User::factory()->create();
        return [ $token, $this->withToken($token)->actingAs($user, 'api') ];
    }

    protected function checkAuthApiMiddleware( array $routes = [])
    {
        foreach( $routes as $route => $method )
        {
            $response = $this->{"{$method}Json"}($route);
            $response->assertStatus(Response::HTTP_UNAUTHORIZED);
            $response->assertJson([ 'message' => 'Unauthenticated.' ]);
        }

    }

}
