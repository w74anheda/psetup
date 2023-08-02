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

    public function definition(): array
    {
        return [
            'first_name'        => fake()->firstName(),
            'last_name'         => fake()->lastName(),
            'gender'            => fake()->randomElement([ 'male', 'female', 'both' ]),
            'phone'             => '0916' . generate_random_digits_with_specefic_length(7),
            'activated_at'      => now(),
            'last_online_at'    => now(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => null,
            'is_active'         => 1,
            'is_new'            => 0,
            'registered_ip'     => fake()->ipv4(),
            'personal_info'     => null
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
                'activated_at' => null,
                'is_active'    => 0,
            ]
        );
    }

    public function completed()
    {
        return $this->state(
            fn(array $attr) => [
                'personal_info' => [
                    'is_completed' => true,
                    'birth_day'    => now()->toString(),
                    'national_id'  => (string) fake()->randomNumber(9, true)
                ],
            ]
        );
    }
    public function registered()
    {
        return $this->state(
            fn(array $attr) => [
                'first_name'        => fake()->firstName(),
                'last_name'         => fake()->lastName(),
                'gender'            => fake()->randomElement([ 'male', 'female', 'both' ]),
                'phone'             => '0916' . generate_random_digits_with_specefic_length(7),
                'activated_at'      => now(),
                'last_online_at'    => now(),
                'email'             => fake()->unique()->safeEmail(),
                'email_verified_at' => null,
                'is_active'         => 1,
                'is_new'            => 0,
                'registered_ip'     => fake()->ipv4(),
                'personal_info'     => null
            ]
        );
    }


}
