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
use App\Services\User\UserService;
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





}
