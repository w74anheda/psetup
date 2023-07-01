<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('states')->truncate();
        DB::table('cities')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $states = State::factory()->count(20)->create();

        foreach( range(0, 20) as $i )
        {
            City::factory()->create([
                'state_id' => fake()->randomElement($states->pluck('id'))
            ]);
        }

    }
}
