<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PassportCustomToken>
 */
class PassportCustomTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id'        => fake()->unique()->uuid(),
            'client_id' => 1,
            'revoked'   => fake()->randomElement([ 0, 1 ]),
        ];
    }

    public function revoked(): static
    {
        return $this->state(fn(array $attributes) => [
            'revoked' => 1,
        ]);
    }

    public function unRevoked(): static
    {
        return $this->state(fn(array $attributes) => [
            'revoked' => 0,
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn(array $attributes) => [
            'expires_at' => now()->subDays(10),
        ]);
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'expires_at' => now()->addDays(10),
        ]);
    }
}
