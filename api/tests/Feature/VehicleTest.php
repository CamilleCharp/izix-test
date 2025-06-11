<?php

namespace Tests\Feature;

use App\Enums\Permissions;
use App\Enums\Roles;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;
    protected VehicleType $vehicleType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);

        $this->user = User::factory()->create();
        $this->user->assignRole(Roles::USER->value);
        $this->admin = User::factory()->create();
        $this->admin->assignRole(Roles::USER, Roles::ADMIN);

        $this->vehicleType = VehicleType::factory()->create();
    }

    public function test_vehicle_index_return_vehicles(): void
    {
        $vehicle = Vehicle::factory()->for($this->user, 'owner')->for($this->vehicleType, 'type')->create();

        $response = $this->getJson('/vehicles');

        $response->assertOk()
            ->assertJsonFragment([
                'uuid' => $vehicle->uuid,
                'plate' => $vehicle->license_plate,
            ]);
    }

    public function test_vehicle_store_create_new_vehicle(): void
    {
        $this->actingAs($this->user);

        $licensePlate = Vehicle::factory()->generateLicensePlate();

        $response = $this->postJson('/vehicles/store', [
            'type' => $this->vehicleType->id,
            'plate' => $licensePlate,
        ]);

        $response->assertCreated()
            ->assertJsonFragment(['plate' => $licensePlate]);

        $this->assertDatabaseHas('vehicles', ['license_plate' => $licensePlate]);
    }


    public function test_vehicle_create_new_vehicle_for_other_user(): void
    {
        $this->actingAs($this->admin);

        $licensePlate = Vehicle::factory()->generateLicensePlate();

        $response = $this->postJson('/vehicles/store', [
            'type' => $this->vehicleType->id,
            'plate' => $licensePlate,
            'owner_id' => $this->user->id,
        ]);

        $response->assertCreated()
            ->assertJsonFragment(['plate' => $licensePlate]);

        $this->assertDatabaseHas('vehicles', [
            'license_plate' => $licensePlate,
            'owner_id' => $this->user->id,
        ]);
    }

    public function test_vehicle_show_return_vehicle(): void
    {
        $vehicle = Vehicle::factory()->for($this->user, 'owner')->for($this->vehicleType, 'type')->create();

        $response = $this->getJson("/vehicles/{$vehicle->uuid}");

        $response->assertOk()
            ->assertJsonFragment(['plate' => $vehicle->license_plate]);
    }

    public function test_vehicle_show_404_on_invalid_uuid(): void
    {
        $response = $this->getJson("/vehicles/aaaa");

        $response->assertNotFound();
    }

    public function test_update_vehicle_modify_vehicle(): void
    {
        $this->actingAs($this->admin);

        $vehicle = Vehicle::factory()->for($this->user, 'owner')->for($this->vehicleType, 'type')->create();

        $response = $this->putJson("/vehicles/update/{$vehicle->uuid}", [
            'plate' => 'UPDATED-PLATE',
        ]);

        $response->assertOk()
            ->assertJsonFragment(['plate' => 'UPDATED-PLATE']);

        $this->assertDatabaseHas('vehicles', ['license_plate' => 'UPDATED-PLATE']);
    }

    public function test_update_vehicle_404_on_invalid_uuid(): void
    {
        $this->actingAs($this->admin);

        $response = $this->putJson("/vehicles/update/aaaa", [
            'plate' => 'UPDATED-PLATE',
        ]);

        $response->assertNotFound();
    }

    public function test_vehicle_destroy_delete_vehicle(): void
    {
        $this->actingAs($this->user);

        $vehicle = Vehicle::factory()->for($this->user, 'owner')->for($this->vehicleType, 'type')->create();

        $response = $this->deleteJson("/vehicles/delete/{$vehicle->uuid}");

        $response->assertOk()
            ->assertJsonFragment(['message' => "Your vehicle with license plate {$vehicle->license_plate} deleted successfully"]);

        $this->assertDatabaseMissing('vehicles', ['uuid' => $vehicle->uuid]);
    }

    public function test_vehicle_destroy_delete_user_vehicle(): void
    {
        $this->actingAs($this->admin);

        $vehicle = Vehicle::factory()->for($this->user, 'owner')->for($this->vehicleType, 'type')->create();

        $response = $this->deleteJson("/vehicles/delete/{$vehicle->uuid}");

        $response->assertOk()
        ->assertJsonFragment(['message' => "Your vehicle with license plate {$vehicle->license_plate} deleted successfully"]);

    
        $this->assertDatabaseMissing('vehicles', ['uuid' => $vehicle->uuid]);
    }

    public function test_vehicle_destroy_404_on_invalid_uuid(): void
    {
        $this->actingAs($this->user);

        $response = $this->deleteJson("/vehicles/delete/aaaa");

        $response->assertNotFound();
    }
}
