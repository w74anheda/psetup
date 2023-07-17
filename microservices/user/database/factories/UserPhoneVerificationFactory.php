<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPhoneVerification>
 */
class UserPhoneVerificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'   => User::factory(),
            'code'      => generate_random_digits_with_specefic_length(env('PHONE_VERIFICATION_CODE_LENGTH')),
            'expire_at' => Carbon::now()->addSeconds(env('PHONE_VERIFICATION_CODE_LIFETIME_SECONDS')),
            'hash'      => Str::uuid(),
        ];
    }
}
