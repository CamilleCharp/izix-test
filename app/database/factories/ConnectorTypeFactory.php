<?php

namespace Database\Factories;

use App\Enums\Current;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConnectorType>
 */
class ConnectorTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // $maxVoltage = fake()->numberBetween(100, 400);
        // $maxCurrent = fake()->numberBetween(40, 500);
        // $maxWatts = $maxVoltage * $maxCurrent;

        // return [
        //     // Use a person name, I couldn't find a better out of the box fake value
        //     'name' => fake()->name(),
        //     'max_voltage' => $maxVoltage,
        //     'max_current' => $maxCurrent,
        //     'max_watts' => $maxWatts,
        //     'current_type' => fake()->randomElement([Current::AC->value, Current::DC->value])
        // ];
    }
}
