<?php

namespace Database\Factories;

use App\Models\Tenant;
use DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->address(),
            'coordinate' => [fake()->latitude(), fake()->longitude],
            'capacity' => fake()->numberBetween(1, 1000),
            'tenant_uuid' => Tenant::factory()
        ];
    }
}
