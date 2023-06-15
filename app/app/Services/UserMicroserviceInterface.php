<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UserMicroserviceInterface
{
    private string|null $endpoint;
    private string|null $token = null;


    public function __construct()
    {
        if ( $this->token = request()->bearerToken() ) {
        }
        $this->endpoint = env( "USER_MICROSERVICE_ENDPOINT" );
    }
    private function request() : PendingRequest
    {
        return Http::baseUrl( $this->endpoint )->withHeaders( [ 'Accept' => 'Application/Json' ] );
    }
    private function authRequest() : PendingRequest
    {
        if ( is_null( $this->token ) ) {
            throw new InvalidArgumentException( "First Set Token By setToken() !" );
        }
        return $this->request()->withToken( $this->token );
    }

    public function setToken( string $token ) : void
    {
        $this->token = $token;
    }

    public function user()
    {
        $user                     = $this->authRequest()->get( "auth/me" )->json();
        $model                    = new User;
        $model->id                = $user[ 'id' ];
        $model->first_name        = $user[ 'first_name' ];
        $model->last_name         = $user[ 'last_name' ];
        $model->gender            = $user[ 'gender' ];
        $model->email             = $user[ 'email' ];
        $model->email_verified_at = $user[ 'email_verified_at' ];
        $model->created_at        = $user[ 'created_at' ];
        $model->updated_at        = $user[ 'updated_at' ];
        return $model;
    }

    public function allows( $ability, $arguments )
    {
        return Gate::forUser( $this->user() )->authorize( $ability, $arguments );
    }
}
