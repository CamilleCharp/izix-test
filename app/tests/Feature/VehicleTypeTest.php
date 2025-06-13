<?php

namespace Tests\Feature;

use App\Enums\Roles;
use App\Models\User;
use App\Models\VehicleType;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleTypeTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeader("API-KEY", env('FAKE_API_KEY'));

        $this->seed(RoleSeeder::class);

        $this->admin = User::factory()->create();
        $this->admin->assignRole(Roles::ADMIN);
    }

    public function test_vehicle_type_index_returns_vehicle_types(): void
    {
        $this->actingAs($this->admin);

        $vehicleType = VehicleType::factory()->create();

        $response = $this->getJson('/api/vehicles/types');

        $response->assertOk()
            ->assertJsonFragment(['name' => $vehicleType->name]);
    }

    public function test_vehicle_type_store_creates_new_type(): void
    {
        $this->actingAs($this->admin);

        $response = $this->postJson('api/vehicles/types/store', data: [
            'name' => 'New EV Type',
            'maximum_ac_input' => fake()->numberBetween(1000, 7000),
            'maximum_dc_input' => fake()->numberBetween(10000, 70000),
            'battery_capacity' => fake()->numberBetween(40000, 80000),
        ]);
        
        $response->assertCreated()
            ->assertJsonFragment(['name' => 'New EV Type']);

        $this->assertDatabaseHas('vehicle_types', ['name' => 'New EV Type']);
    }

    public function test_vehicle_type_show_returns_type(): void
    {
        $this->actingAs($this->admin);

        $vehicleType = VehicleType::factory()->create();

        $response = $this->getJson("/api/vehicles/types/{$vehicleType->id}");

        $response->assertOk()
            ->assertJsonFragment(['name' => $vehicleType->name]);
    }

    public function test_vehicle_type_show_404_on_invalid_id(): void
    {
        $this->actingAs($this->admin);

        $response = $this->getJson("/api/vehicles/types/999");

        $response->assertNotFound();
    }

    public function test_vehicle_type_update_modifies_type(): void
    {
        $this->actingAs($this->admin);

        $vehicleType = VehicleType::factory()->create();

        $response = $this->putJson("/api/vehicles/types/update/{$vehicleType->id}", [
            'name' => 'Updated Name',
        ]);

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Updated Name']);

        $this->assertDatabaseHas('vehicle_types', ['name' => 'Updated Name']);
    }

    public function test_vehicle_type_update_404_on_invalid_id(): void
    {
        $this->actingAs($this->admin);

        $response = $this->putJson("/api/vehicles/types/update/999", [
            'name' => 'Updated Name',
        ]);

        $response->assertNotFound();
    }

    public function test_vehicle_type_destroy_deletes_type(): void
    {
        $this->actingAs($this->admin);

        $vehicleType = VehicleType::factory()->create();

        $response = $this->deleteJson("/api/vehicles/types/delete/{$vehicleType->id}");

        $response->assertOk()
            ->assertJsonFragment(['message' => "Vehicle type {$vehicleType->name} deleted successfully"]);

        $this->assertDatabaseMissing('vehicle_types', ['id' => $vehicleType->id]);
    }

    public function test_vehicle_type_destroy_404_on_invalid_id(): void
    {
        $this->actingAs($this->admin);

        $response = $this->deleteJson("/api/vehicles/types/delete/999");

        $response->assertNotFound();
    }
}
