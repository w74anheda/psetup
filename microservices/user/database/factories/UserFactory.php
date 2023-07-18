<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;

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
            // 'gender'            => fake()->randomElement([ 'male', 'female', 'both' ]),
            'gender'            => new Sequence('male', 'female', 'both'),
            'phone'             => fake()->unique()->phoneNumber(),
            'activated_at'      => null,
            'last_online_at'    => null,
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => null,
            'is_active'         => 0,
            'is_new'            => 1,
            'registered_ip'     => fake()->ipv4(),
            'personal_info'     => null,
        ];
    }


}
