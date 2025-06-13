<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Station;
use App\Models\StationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Station::truncate();

        $locations = [
            'Downtown Parking Lot',
            'Shopping Mall EV Zone',
            'Highway Rest Stop',
            'Airport Charging Area',
            'Residential Complex EV Station',
        ];

        foreach ($locations as $locationName) {
            Station::factory()->create([
                'name' => 'EV-' . fake()->unique()->bothify('??###'),
                'spot' => fake()->numberBetween(1, 100),
                'type_id' => StationType::inRandomOrder()->first()->id,
                'location_uuid' => Location::inRandomOrder()->first()->uuid, // Replace with actual location logic if needed
            ]);
        }
    }
}
