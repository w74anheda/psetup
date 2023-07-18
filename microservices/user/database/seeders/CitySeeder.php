<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    use RefreshDatabase;

    public function run(): void
    {
        State::factory()
            ->has(City::factory()->count(10))
            ->count(50)
            ->create();
    }
}
