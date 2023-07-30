<?php

namespace Tests\Feature\Casts;

use App\Casts\PersonalInfoCast as CastsPersonalInfoCast;
use App\Models\User;
use Tests\TestCase;

class PersonalInfoCast extends TestCase
{

    public function test_example(): void
    {
        $user = User::factory()->completed()->create();
        $cast = new CastsPersonalInfoCast;

        dd($cast->get(
            $user,
            'personal_info',
            $user->getAttributes()['personal_info'],
            $user->getAttributes()
        ));
    }
}
