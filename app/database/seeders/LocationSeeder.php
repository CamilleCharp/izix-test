<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::truncate();

        $locations = [
            [
                'name' => 'Downtown Parking Lot',
                'capacity' => 60,
                'coordinate' => [40.712776, -74.005974],
                'tenant_uuid' => Tenant::inRandomOrder()->first()->uuid,
            ],
            [
                'name' => 'Shopping Mall EV Zone',
                'capacity' => 150,
                'coordinate' => [34.052235, -118.243683],
                'tenant_uuid' => Tenant::inRandomOrder()->first()->uuid,
            ],
            [
                'name' => 'Highway Rest Stop',
                'capacity' => 80,
                'coordinate' => [36.778259, -119.417931],
                'tenant_uuid' => Tenant::inRandomOrder()->first()->uuid,
            ],
            [
                'name' => 'Airport Charging Area',
                'capacity' => 160,
                'coordinate' => [37.621313, -122.378955],
                'tenant_uuid' => Tenant::inRandomOrder()->first()->uuid,
            ],
            [
                'name' => 'Residential Complex EV Station',
                'capacity' => 10,
                'coordinate' => [41.878113, -87.629799],
                'tenant_uuid' => Tenant::inRandomOrder()->first()->uuid,
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
