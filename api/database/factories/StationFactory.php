<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\StationType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Station>
 */
class StationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'spot' => fake()->numberBetween(1, 1000),
            'type_id' => StationType::factory(),
            'location_uuid' => Location::factory(),
        ];
    }
}
