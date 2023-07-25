<?php

namespace Tests\Feature\Services;

use App\DTO\UserCompleteRegisterDTO;
use App\Models\User;
use App\Services\AuthService;
use App\Services\Passport\CustomToken;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{

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
        [ $_, $verification ] = UserService::loginPhoneRequest($user->phone);
        $dto                  = $this->createDto();
        [ $isOK, $token ]     = UserService::loginPhoneVerify(
            $user,
            $verification->hash,
            $verification->code,
            $dto
        );
        return $token;
    }

    public function testTokenDestroy(): void
    {
        $user = User::factory()->isNew()->create();
        $this->createToken($user);
        $token = $user->tokens()->first();
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


}
