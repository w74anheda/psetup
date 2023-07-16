<?php

namespace Tests\Unit;

use App\Casts\PersonalInfoCast;
use App\Console\Kernel;
use App\Models\Address;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Traits\HasAddress;
use App\Models\Traits\HasIp;
use App\Models\Traits\HasPhoneVerification;
use App\Models\User;
use App\Models\UserIp;
use App\Models\UserPhoneVerification;
use App\Presenters\PresentAble;
use App\Services\Acl\HasPermission;
use App\Services\Acl\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Application;
// use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Tests\TestCase;
use App\Presenters\User\Api as UserApiPresenter;
use App\Services\UserService;
use Carbon\Carbon;
use Ramsey\Uuid\Lazy\LazyUuidFromString;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

    public function test_check_user_attributes()
    {
        $user       = User::factory()->create();
        return $user;
    }


    /**
     * @depends test_check_user_attributes
     */
    public function test_user_relations($user)
    {
        $this->assertTrue($user->ips() instanceof HasMany);
        $this->assertTrue($user->ips()->getModel() instanceof UserIp);

        $this->assertTrue($user->addresses() instanceof HasMany);
        $this->assertTrue($user->addresses()->getModel() instanceof Address);

        $this->assertTrue($user->phoneVerifications() instanceof HasOne);
        $this->assertTrue($user->phoneVerifications()->getModel() instanceof UserPhoneVerification);

        $this->assertTrue($user->roles() instanceof BelongsToMany);
        $this->assertTrue($user->roles()->getModel() instanceof Role);

        $this->assertTrue($user->permissions() instanceof BelongsToMany);
        $this->assertTrue($user->permissions()->getModel() instanceof Permission);
    }

    /**
     * @depends test_check_user_attributes
     */
    public function test_user_has_presenter($user)
    {
        $this->assertTrue($user->present() instanceof UserApiPresenter);
    }

    /**
     * @depends test_check_user_attributes
     */
    public function test_user_model_traits($user)
    {

        $this->assertSame(
            array_keys(class_uses(User::class)),
            [
                HasApiTokens::class,
                HasFactory::class,
                Notifiable::class,
                HasPermission::class,
                HasRoles::class,
                PresentAble::class,
                HasPhoneVerification::class,
                HasAddress::class,
                HasIp::class
            ]
        );

        $this->assertEquals(get_parent_class(User::class), Authenticatable::class);

        $this->assertFalse($user->incrementing);
        $this->assertEquals($user->getKeyType(), 'string');
    }

    public function test_user_service_first_or_create_user_and_activation_handler()
    {
        $phone = fake()->phoneNumber();
        $ip    = fake()->ipv4();
        $user  = UserService::firstOrCreateUser($phone, $ip);

        $this->assertTrue($user instanceof User);
        $this->assertTrue($user->phone == $phone);
        $this->assertTrue($user->registered_ip == $ip);
        $this->assertTrue($user->isNew());

        $date = Carbon::parse('2023-07-14 10:00:00');

        Carbon::setTestNow($date);
        UserService::activateHandler(
            $user,
            'masoud_test',
            'nazarporr_test',
            'male'
        );

        $this->assertFalse($user->isNew());
        $this->assertTrue($user->first_name == 'masoud_test');
        $this->assertTrue($user->last_name == 'nazarporr_test');
        $this->assertTrue($user->gender == 'male');
        $this->assertTrue($user->activated_at == $date);
        Carbon::setTestNow();

    }






}
