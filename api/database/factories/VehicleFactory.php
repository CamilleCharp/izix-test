<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\VehicleType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'license_plate' => $this->generateLicensePlate(),
            'type_id' => VehicleType::factory(),
            'owner_id' => User::factory()
        ];
    }

    private function generateLicensePlate()
    {
        $firstSection = fake()->numberBetween(1,9);
        $secondSection = Str::random(3);
        $thirdSection = Str::padLeft(fake()->numberBetween(1,2), 3, '0');

        return $firstSection . '-' . $secondSection . '-' . $thirdSection;
    }
}
