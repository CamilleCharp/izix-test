<?php

namespace Database\Factories;

use App\Enums\Current;
use App\Models\StationLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StationType>
 */
class StationTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Type-' . fake()->unique()->bothify('??###'),
            'current' => fake()->randomElement([Current::AC->value, Current::DC->value]),
            'power' => fake()->numberBetween(1000, 100000),
            'station_level_id' => StationLevel::inRandomOrder()->first()->id,
        ];
    }
}
