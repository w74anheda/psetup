<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name'        => fake()->firstName(),
            'last_name'         => fake()->lastName(),
            'gender'            => fake()->randomElement([ 'male', 'female', 'both' ]),
            'phone'             => fake()->unique()->phoneNumber(),
            'activated_at'      => now(),
            'last_online_at'    => null,
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => null,
            'is_active'         => 1,
            'is_new'            => 0,
            'registered_ip'     => fake()->ipv4(),
            'personal_info'     => null,
        ];
    }

    public function male()
    {
        return $this->state(fn(array $attr) => [ 'gender' => 'male' ]);
    }

    public function super()
    {
        return $this->state(fn(array $attr) => [ 'phone' => env('SUPER_ADMIN_PHONE_NUMBER') ]);
    }

    public function isNew()
    {
        return $this->state(
            fn(array $attr) => [
                'first_name'        => null,
                'last_name'         => null,
                'gender'            => null,
                'phone'             => fake()->unique()->phoneNumber(),
                'activated_at'      => null,
                'last_online_at'    => null,
                'email'             => null,
                'email_verified_at' => null,
                'is_active'         => 0,
                'is_new'            => 1,
                'registered_ip'     => null,
                'personal_info'     => null,
            ]
        );
    }

    public function notActive()
    {
        return $this->state(
            fn(array $attr) => [
                'activated_at'      => null,
                'is_active'         => 0,
            ]
        );
    }

    public function active()
    {
        return $this->state(
            fn(array $attr) => [
                'activated_at'      => now(),
                'is_active'         => 1,
            ]
        );
    }


}
