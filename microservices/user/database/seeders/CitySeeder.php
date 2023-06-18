<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Exception;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = State::factory()->count(20)->create();

        foreach( range(0, 20) as $i )
        {
            City::factory()->create([
                'state_id' => fake()->randomElement($states->pluck('id'))
            ]);
        }

    }
}
