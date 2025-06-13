<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VehicleType>
 */
class VehicleTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' ' . fake()->word() . ' (' . fake()->numberBetween(40, 100) . 'kWh)',
            'maximum_ac_input' => fake()->numberBetween(10000, 50000),
            'maximum_dc_input' => fake()->numberBetween(500000, 100000),
            'battery_capacity' => fake()->numberBetween(40000, 100000),
        ];
    }
}
