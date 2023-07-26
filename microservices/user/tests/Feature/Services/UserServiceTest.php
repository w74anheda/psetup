<?php

namespace Tests\Feature\Services;

use App\DTO\UserCompleteRegisterDTO;
use App\Models\User;
use App\Models\UserPhoneVerification;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use Tests\TestCase;
use App\Events\Auth\Login\PhoneNumber\Request as PhoneNumberRequestEvent;
use Illuminate\Support\Str;
use InvalidArgumentException;

class UserServiceTest extends TestCase
{

    public function testFirstOrCreateReturnUser()
    {
        $user = UserService::firstOrCreateUser(
            $phone = fake()->phoneNumber()
        );

        $this->assertTrue($user instanceof User);
        $this->assertTrue($user->phone == $phone);
    }

    public function testFirstOrCreateWithIP()
    {
        $phone = fake()->phoneNumber();
        $ip    = fake()->ipv4();
        $user  = UserService::firstOrCreateUser($phone, $ip);
        $this->assertTrue($user->registered_ip == $ip);
    }

    public function testFirstOrCreateWithoutIP()
    {
        $phone = fake()->phoneNumber();
        $user  = UserService::firstOrCreateUser($phone);

        $this->assertTrue($user instanceof User);
    }

    public function testFirstOrCreateIsNew()
    {
        $phone = fake()->phoneNumber();
        $user  = UserService::firstOrCreateUser($phone);
        $this->assertTrue($user->isNew());
    }

    public function testCompleteRegisterForIsNewUser()
    {
        $user = User::factory()->isNew()->create();
        $this->assertTrue($user->isNew());
        $dto = $this->createDto();

        $date = Carbon::parse('2023-07-14 10:00:00');
        Carbon::setTestNow($date);

        $user = UserService::completeRegister(
            $user,
            $dto,
            $date
        );

        $this->assertFalse($user->isNew());
        $this->assertTrue($user->is_active);
        $this->assertEquals($user->first_name, $dto->first_name);
        $this->assertEquals($user->last_name, $dto->last_name);
        $this->assertEquals($user->gender, $dto->gender);
        $this->assertTrue($user->activated_at == $date);
    }

    public function testCompleteRegisterForIsNewUserWithEmptyDTO()
    {
        $user = User::factory()->isNew()->create();
        $this->assertTrue($user->isNew());
        $dto = $this->createDto(false);
        $this->expectException(InvalidArgumentException::class);
        $user = UserService::completeRegister($user, $dto);
    }

    public function testCompleteRegisterForIsNotNewUser()
    {
        $user     = User::factory()->create();
        $userData = $user->toArray();

        $dto = $this->createDto();

        $this->assertFalse($user->isNew());
        $this->assertTrue($user->isActive());

        $date = Carbon::parse('2023-07-14 10:00:00');
        Carbon::setTestNow($date);

        $user = UserService::completeRegister(
            $user,
            $dto,
            $date
        );

        $this->assertFalse($user->isNew());
        $this->assertTrue($user->is_active);
        $this->assertEquals($user->first_name, $userData['first_name']);
        $this->assertEquals($user->last_name, $userData['last_name']);
        $this->assertEquals($user->gender, $userData['gender']);
        $this->assertTrue(
            Carbon::parse($user->activated_at)
            ==
            Carbon::parse($userData['activated_at'])
        );

        Carbon::setTestNow();
    }

    public function testCompleteRegisterReturnUser()
    {
        $dto  = $this->createDto();
        $user = User::factory()->create();
        $date = Carbon::parse('2023-07-14 10:00:00');

        $_user = UserService::completeRegister(
            $user,
            $dto,
            $date
        );
        $this->assertTrue($_user instanceof User);
        $this->assertTrue($_user->id instanceof $user->id);
    }

    public function testCompleteRegisterPassNullActivatedAtDatetime()
    {
        $dto  = $this->createDto();
        $user = User::factory()->isNew()->create();
        $this->assertTrue($user->isNew());

        $now = Carbon::parse('2023-07-14 10:00:00');
        Carbon::setTestNow($now);

        $user = UserService::completeRegister(
            $user,
            $dto
        );

        $this->assertTrue(
            Carbon::parse($user->activated_at)
            ==
            Carbon::parse($now)
        );
    }

    public function testSetLastOnlineAtWithoutPassDate()
    {
        $user = User::factory()->create();
        Carbon::setTestNow('2023-01-01 12:00:00');
        UserService::setLastOnlineAt($user);
        $this->assertTrue(
            $user->last_online_at
            ==
            now()
        );
    }

    public function testSetLastOnlineAtWithPassDate()
    {
        $user = User::factory()->create();
        $date = Carbon::parse('2023-02-01 12:00:00');
        UserService::setLastOnlineAt($user, $date);
        $this->assertTrue($user->last_online_at == $date);
    }

    public function testSetLastOnlineAtReturnUser()
    {
        $user  = User::factory()->create();
        $_user = UserService::setLastOnlineAt($user);
        $this->assertTrue($_user instanceof User);
        $this->assertTrue($_user->id == $user->id);
    }

    public function testLoginPhoneRequest()
    {
        $data = User::factory()->make()->toArray();
        $user = User::factory()->state([ 'phone' => $data['phone'] ])->create();

        $this->mock(
            AuthService::class,
            function (MockInterface $mock) use ($user)
            {
                $mock
                    ->shouldReceive('generateVerificationCode')
                    ->andReturn(UserPhoneVerification::factory()->for($user)->create())
                    ->times(1);
            }
        );

        Event::fake();

        [ $user, $verification ] = UserService::loginPhoneRequest($data['phone']);

        $this->assertEquals($user->phone, $data['phone']);
        $this->assertTrue($verification instanceof UserPhoneVerification);
        $this->assertTrue($verification->user->id == $user->id);

        Event::assertDispatched(PhoneNumberRequestEvent::class, 1);

    }

    public function testLoginPhoneVerifyForNewUser()
    {
        $user = User::factory()->isNew()->create();
        $dto  = $this->createDto();
        $dto->validate();
        [ $user, $verification ] = UserService::loginPhoneRequest($user->phone);

        $this->mock(
            AuthService::class,
            function (MockInterface $mock)
            {
                $mock
                    ->shouldReceive('clearVerificationCode')
                    ->once();
            }
        );

        $this->mock(
            UserService::class,
            function (MockInterface $mock)
            {
                $mock
                    ->shouldReceive('completeRegister')
                    ->once();
            }
        );

        [ $isOK, $tokens ] = UserService::loginPhoneVerify(
            $user,
            $verification->hash,
            $verification->code,
            $dto
        );

        $this->assertTrue($isOK);
        $this->assertTrue($tokens['token_type'] == 'Bearer');
        $this->assertTrue($tokens['expires_in'] == 1296000);
        $this->assertArrayHasKey('token_type', $tokens);
        $this->assertArrayHasKey('expires_in', $tokens);
        $this->assertArrayHasKey('access_token', $tokens);
        $this->assertArrayHasKey('refresh_token', $tokens);
    }

    public function testLoginPhoneVerifyForNewUserWithEmptyDTO()
    {
        $user = User::factory()->isNew()->create();
        $dto  = $this->createDto(false);

        [ $user, $verification ] = UserService::loginPhoneRequest($user->phone);

        [ $isOK, $tokens ] = UserService::loginPhoneVerify(
            $user,
            $verification->hash,
            $verification->code,
            $dto
        );
        $this->assertFalse($isOK);
        $this->assertNull($tokens);
    }

    public function testLoginPhoneVerifyForExistingUserWithEmptyDTO()
    {
        $user = User::factory()->create();
        $dto  = $this->createDto(false);

        [ $user, $verification ] = UserService::loginPhoneRequest($user->phone);

        [ $isOK, $tokens ] = UserService::loginPhoneVerify(
            $user,
            $verification->hash,
            $verification->code,
            $dto
        );

        $this->assertTrue($isOK);
        $this->assertTrue($tokens['token_type'] == 'Bearer');
        $this->assertTrue($tokens['expires_in'] == 1296000);
        $this->assertArrayHasKey('token_type', $tokens);
        $this->assertArrayHasKey('expires_in', $tokens);
        $this->assertArrayHasKey('access_token', $tokens);
        $this->assertArrayHasKey('refresh_token', $tokens);
    }

    public function testLoginPhoneVerifyForExistingUserWithFilledDTO()
    {
        $data = User::factory()->make()->toArray();
        $user = User::factory()->state($data)->create();
        $dto  = $this->createDto(false);

        [ $user, $verification ] = UserService::loginPhoneRequest($user->phone);

        [ $isOK, $tokens ] = UserService::loginPhoneVerify(
            $user,
            $verification->hash,
            $verification->code,
            $dto
        );

        $this->assertTrue($isOK);
        $this->assertTrue($tokens['token_type'] == 'Bearer');
        $this->assertTrue($tokens['expires_in'] == 1296000);
        $this->assertArrayHasKey('token_type', $tokens);
        $this->assertArrayHasKey('expires_in', $tokens);
        $this->assertArrayHasKey('access_token', $tokens);
        $this->assertArrayHasKey('refresh_token', $tokens);
        $this->assertEquals($user->first_name, $data['first_name']);
        $this->assertEquals($user->last_name, $data['last_name']);
        $this->assertEquals($user->gender, $data['gender']);
    }

    public function testLoginPhoneVerifyWithInvalidHash()
    {
        $user = User::factory()->create();
        $dto  = $this->createDto();

        [ $isOK, $tokens ] = UserService::loginPhoneVerify(
            $user,
            'invalid hash',
            'invalid code',
            $dto
        );
        $this->assertFalse($isOK);
        $this->assertNull($tokens);
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
}
