<?php

namespace Database\Seeders;

use App\Models\StationLevel;
use App\Models\StationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StationType::truncate();

        $stationTypes = [
            [
                'name' => 'Level 2 AC Charger',
                'current' => 'AC',
                'power' => 22000,
                'station_level_id' => StationLevel::where('level', 1)->first()->id, // Assuming station levels are predefined
            ],
            [
                'name' => 'DC Fast Charger',
                'current' => 'DC',
                'power' => 50000,
                'station_level_id' => StationLevel::where('level', 2)->first()->id,
            ],
            [
                'name' => 'Ultra-Fast DC Charger',
                'current' => 'DC',
                'power' => 150000,
                'station_level_id' => StationLevel::where('level', 3)->first()->id,
            ],
        ];

        foreach ($stationTypes as $type) {
            StationType::create($type);
        }
    }
}
