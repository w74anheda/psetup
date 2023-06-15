<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'gender' => fake()->randomElement(['male', 'female', 'both']),
            'phone' => fake()->phoneNumber(),
            'activated_at' => today(),
            'last_online_at' => today(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => Null,
            'is_active' => true,
            'registered_ip' => fake()->ipv4(),
            'personal_info' => null,
            // 'password' => bcrypt('@123'), // password
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
