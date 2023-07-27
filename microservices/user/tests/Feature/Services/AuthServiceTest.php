<?php

namespace Tests\Feature\Services;

use App\DTO\UserCompleteRegisterDTO;
use App\Models\User;
use App\Services\Auth\AuthService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{

    private function getTokenByAccessToken(User $user, string $accessToken)
    {
        $tokenID = (new Parser(new JoseEncoder()))->parse($accessToken)->claims()->all()['jti'];
        $token   = $user->tokens()->where('id', $tokenID)->first();
        return $token;
    }

    private function createDto(bool $filled = true): UserCompleteRegisterDTO
    {
        $data = User::factory()->make()->toArray();

        $dto = (new UserCompleteRegisterDTO);
        if($filled)
            $dto->setFirstName($data['first_name'])
                ->setLastName($data['last_name'])
                ->setGender($data['gender']);
        return $dto;
    }

    private function createToken(User $user)
    {
        $verification = AuthService::generateVerificationCode($user);
        $token        = AuthService::getAccessAndRefreshTokenByPhone(
            $user,
            $verification->hash,
            $verification->code
        );

        return $token;
    }

    public function testTokenDestroy(): void
    {
        $user        = User::factory()->isNew()->create();
        $bearerToken = $this->createToken($user)['access_token'];
        $token       = $user->tokens()->first();
        $this->mock(
            RefreshTokenRepository::class,
            function ($mock) use ($token)
            {
                $mock
                    ->shouldReceive('revokeRefreshToken')
                    ->with($token->id)
                    ->once();
            }
        );

        $this->assertTrue(
            AuthService::tokenDestroy($token)
        );
        $this->assertTrue($token->revoked);
    }

    public function testAllTokenDestroy(): void
    {
        $user = User::factory()->isNew()->create();

        foreach( range(1, 10) as $_i )
        {
            $this->createToken($user);
        }

        $this->mock(
            RefreshTokenRepository::class,
            function ($mock)
            {
                $mock
                    ->shouldReceive('revokeRefreshToken')
                    ->times(10);
            }
        );

        foreach( $user->tokens as $token )
        {
            $this->assertTrue(
                AuthService::tokenDestroy($token)
            );
            $this->assertTrue($token->revoked);
        }
    }

    public function testGenerateVerificationCodeWithoutPassCode(): void
    {
        Carbon::setTestNow('2023-01-01 00:00:00');
        $user             = User::factory()->create();
        $verificationCode = AuthService::generateVerificationCode($user);

        $this->assertDatabaseHas($verificationCode->getTable(), $verificationCode->toArray());
        $this->assertTrue($user->id == $verificationCode->user->id);

        $expireAt = now()->addSeconds(
            env('PHONE_VERIFICATION_CODE_LIFETIME_SECONDS')
        );

        $this->assertTrue($verificationCode->expire_at == $expireAt);
    }

    public function testGenerateVerificationCodeWithPassCode(): void
    {
        $user             = User::factory()->create();
        $code             = Str::random(5);
        $verificationCode = AuthService::generateVerificationCode($user, $code);

        $this->assertDatabaseHas($verificationCode->getTable(), $verificationCode->toArray());
        $this->assertTrue($user->id == $verificationCode->user->id);
        $this->assertTrue($code == $verificationCode->code);
    }

    public function testClearVerificationCodeWithValidHash(): void
    {
        $user             = User::factory()->create();
        $verificationCode = AuthService::generateVerificationCode($user);
        $result           = AuthService::clearVerificationCode($user, $verificationCode->hash);

        $this->assertTrue($result);
        $this->assertDatabaseMissing($verificationCode->getTable(), $verificationCode->toArray());
    }

    public function testClearVerificationCodeWithInValidHash(): void
    {
        $user   = User::factory()->create();
        $result = AuthService::clearVerificationCode($user, Str::random(20));
        $this->assertFalse($result);
    }

    public function testGetAccessAndRefreshTokenByPhone()
    {
        $user         = User::factory()->isNew()->create();
        $verification = AuthService::generateVerificationCode($user);

        $token = AuthService::getAccessAndRefreshTokenByPhone(
            $user,
            $verification->hash,
            $verification->code
        );

        $this->assertArrayHasKey('token_type', $token);
        $this->assertArrayHasKey('expires_in', $token);
        $this->assertArrayHasKey('access_token', $token);
        $this->assertArrayHasKey('refresh_token', $token);
        $this->assertTrue($token['token_type'] == 'Bearer');
        $this->assertTrue($token['expires_in'] == 1296000);

        $tokenModel = $this->getTokenByAccessToken($user, $token['access_token']);
        $this->assertTrue($tokenModel->user->id == $user->id);
    }

    public function testGetAccessAndRefreshTokenByPhoneWithInvalidHashAndCode()
    {
        $user = User::factory()->isNew()->create();

        $token = AuthService::getAccessAndRefreshTokenByPhone(
            $user,
            'invalid hash',
            'invalid code'
        );

        $this->assertArrayHasKey('error', $token);
        $this->assertArrayHasKey('error_description', $token);
        $this->assertArrayHasKey('message', $token);
        $this->assertTrue($token['error'] == 'invalid_grant');
        $this->assertTrue($token['error_description'] == 'The user credentials were incorrect.');
        $this->assertTrue($token['message'] == 'The user credentials were incorrect.');
    }

    public function testGetAccessAndRefreshTokenByPhoneWithCustomUserAgentAndIp()
    {
        $user         = User::factory()->isNew()->create();
        $verification = AuthService::generateVerificationCode($user);

        $userAgent = fake()->userAgent();
        $ip        = fake()->ipv4();
        $token     = AuthService::getAccessAndRefreshTokenByPhone(
            $user,
            $verification->hash,
            $verification->code,
            [
                'User-Agent' => $userAgent,
                'ip-address' => $ip
            ]
        );

        $tokenModel = $this->getTokenByAccessToken($user, $token['access_token']);

        $this->assertEquals($tokenModel->user_agent, $userAgent);
        $this->assertEquals($tokenModel->ip_address, $ip);
    }

    public function testRefreshAccessToken()
    {
        $user         = User::factory()->isNew()->create();
        $verification = AuthService::generateVerificationCode($user);

        $token = AuthService::getAccessAndRefreshTokenByPhone(
            $user,
            $verification->hash,
            $verification->code
        );

        $newToken = AuthService::refreshAccessToken($token['refresh_token']);

        $this->assertArrayHasKey('token_type', $newToken);
        $this->assertArrayHasKey('expires_in', $newToken);
        $this->assertArrayHasKey('access_token', $newToken);
        $this->assertArrayHasKey('refresh_token', $newToken);
        $this->assertTrue($newToken['token_type'] == 'Bearer');
        $this->assertTrue($newToken['expires_in'] == 1296000);

        $tokenModel = $this->getTokenByAccessToken($user, $newToken['access_token']);
        $this->assertTrue($tokenModel->user->id == $user->id);
    }

    public function testRefreshAccessTokenWithCustomUserAgentAndIp()
    {
        $user         = User::factory()->isNew()->create();
        $verification = AuthService::generateVerificationCode($user);

        $token     = AuthService::getAccessAndRefreshTokenByPhone(
            $user,
            $verification->hash,
            $verification->code
        );
        $userAgent = fake()->userAgent();
        $ip        = fake()->ipv4();

        $newToken = AuthService::refreshAccessToken(
            $token['refresh_token'],
            [
                'User-Agent' => $userAgent,
                'ip-address' => $ip
            ]
        );

        $tokenModel = $this->getTokenByAccessToken($user, $newToken['access_token']);

        $this->assertEquals($tokenModel->user_agent, $userAgent);
        $this->assertEquals($tokenModel->ip_address, $ip);
    }

    public function testRefreshAccessTokenWithInvalidRefreshToken()
    {
        $newToken = AuthService::refreshAccessToken('invalid refresh token');

        $this->assertArrayHasKey('error', $newToken);
        $this->assertArrayHasKey('error_description', $newToken);
        $this->assertArrayHasKey('message', $newToken);
        $this->assertTrue($newToken['error'] == 'invalid_request');
        $this->assertTrue($newToken['message'] == 'The refresh token is invalid.');
    }

    public function testSessions()
    {
        $user  = User::factory()->isNew()->create();
        $token = $this->createToken($user);

        $count = rand(2, 10);
        foreach( range(1, $count) as $_ )
        {
            $this->createToken($user);
        }

        $sessions = AuthService::sessions($user, $token['access_token']);
        $this->assertCount($count + 1, $sessions);

        $this->assertEquals(
            AuthService::getTokenModelByAccessToken($token['access_token'])->id,
            $sessions[0]['id']
        );

        $this->assertTrue($sessions[0]['current']);

        $this->assertArrayHasKey('id', $sessions[0]);
        $this->assertArrayHasKey('user_agent', $sessions[0]);
        $this->assertArrayHasKey('ip_address', $sessions[0]);
        $this->assertArrayHasKey('created_at', $sessions[0]);
        $this->assertArrayHasKey('expires_at', $sessions[0]);
        $this->assertArrayHasKey('current', $sessions[0]);
    }

    public function testSessionsWithInvalidAccessToken()
    {
        $user  = User::factory()->isNew()->create();
        $count = rand(2, 10);

        foreach( range(1, $count) as $_ )
        {
            $this->createToken($user);
        }

        $sessions = AuthService::sessions($user, 'invalid access token');
        $this->assertCount($count, $sessions);

        foreach( $sessions as $session )
        {
            $this->assertFalse($session['current']);
        }
    }

    public function testgetTokenModelByAccessToken()
    {
        $user  = User::factory()->isNew()->create();
        $token = $this->createToken($user);

        $tokenModel            = AuthService::getTokenModelByAccessToken($token['access_token'])->toArray();
        $tokenModel['revoked'] = 0;
        $this->assertDatabaseHas(
            (new(Passport::tokenModel()))->getTable(),
            [ 'id' => $tokenModel['id'] ]
        );
    }

    public function testgetTokenModelByAccessTokenWithInvalidAccessToken()
    {
        $tokenModel = AuthService::getTokenModelByAccessToken('invalid access token');
        $this->assertNull($tokenModel);
    }



}
