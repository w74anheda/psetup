<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'city_id'      => City::factory(),
            'full_address' => fake()->streetAddress(),
            'house_number' => (string) fake()->numberBetween(100, 1000),
            'unit_number'  => (string) fake()->numberBetween(1, 10),
            'postalcode'   => (string) generate_random_digits_with_specefic_length(10),
            'latitude'     => fake()->latitude(),
            'longitude'    => fake()->longitude(),
        ];
    }
}
