<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VehicleType::truncate();

        $vehicleTypes = [
            [
                'name' => 'Tesla Model S (100kWh)',
                'maximum_ac_input' => 11000,
                'maximum_dc_input' => 250000,
                'battery_capacity' => 100000,
            ],
            [
                'name' => 'Nissan Leaf (40kWh)',
                'maximum_ac_input' => 6600,
                'maximum_dc_input' => 50000,
                'battery_capacity' => 40000,
            ],
            [
                'name' => 'Chevrolet Bolt EV (65kWh)',
                'maximum_ac_input' => 7200,
                'maximum_dc_input' => 55000,
                'battery_capacity' => 65000,
            ],
            [
                'name' => 'BMW i3 (42kWh)',
                'maximum_ac_input' => 7400,
                'maximum_dc_input' => 50000,
                'battery_capacity' => 42000,
            ],
            [
                'name' => 'Hyundai Kona Electric (64kWh)',
                'maximum_ac_input' => 7200,
                'maximum_dc_input' => 75000,
                'battery_capacity' => 64000,
            ],
        ];

        foreach ($vehicleTypes as $type) {
            VehicleType::create($type);
        }
    }
}
