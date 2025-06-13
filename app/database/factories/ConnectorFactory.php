<?php

namespace Database\Factories;

use App\Models\ConnectorType;
use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Connector>
 */
class ConnectorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'station_uuid' => Station::factory(),
            'type_id' => ConnectorType::inRandomOrder()->first()->id,
        ];
    }
}
