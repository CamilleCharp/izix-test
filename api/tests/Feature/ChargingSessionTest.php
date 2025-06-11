<?php

namespace Tests\Feature;

use App\Models\Connector;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StationLevelSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChargingSessionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(StationLevelSeeder::class);

        $this->vehicle = Vehicle::factory()->create();
        $this->connector = Connector::factory()->create();
    }

    public function test_charging_session_can_be_started(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'API-KEY' => env('SIMULATOR_API_KEY'),
        ])->post('/charging-sessions/start', [
            'vehicle_uuid' => $this->vehicle->uuid,
            'connector_uuid' => $this->connector->uuid,
            'starting_battery_percent' => 90
        ]);

        $response->assertCreated();
        $response->assertJsonStructure(['session_uuid', 'token']);
    }

    public function test_charging_session_cannot_be_started_without_api_key(): void
    {
        $response = $this->post('/charging-sessions/start', [
            'vehicle_uuid' => $this->vehicle->uuid,
            'connector_uuid' => $this->connector->uuid,
            'starting_battery_percent' => 90
        ]);

        $response->assertForbidden();
    }

    public function test_charging_session_cannot_be_started_without_valid_vehicle_uuid(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'API-KEY' => env('SIMULATOR_API_KEY'),
        ])->post('/charging-sessions/start', [
            'vehicle_uuid' => 'aaa',
            'connector_uuid' => $this->connector->uuid,
            'starting_battery_percent' => 90
        ]);

        $response->assertUnprocessable();
    }

    public function test_charging_session_cannot_be_started_without_valid_connector_uuid(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'API-KEY' => env('SIMULATOR_API_KEY'),
        ])->post('/charging-sessions/start', [
            'vehicle_uuid' => $this->vehicle->uuid,
            'connector_uuid' => 'aaa',
            'starting_battery_percent' => 90
        ]);
        
        $response->assertUnprocessable();
    }

    public function test_charging_session_cannot_be_started_with_negative_battery(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'API-KEY' => env('SIMULATOR_API_KEY'),
        ])->post('/charging-sessions/start', [
            'vehicle_uuid' => $this->vehicle->uuid,
            'connector_uuid' => $this->connector->uuid,
            'starting_battery_percent' => -90
        ]);

        $response->assertUnprocessable();
    }

    public function test_charging_session_cannot_be_started_with_overcharged_battery(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'API-KEY' => env('SIMULATOR_API_KEY'),
        ])->post('/charging-sessions/start', [
            'vehicle_uuid' => $this->vehicle->uuid,
            'connector_uuid' => $this->connector->uuid,
            'starting_battery_percent' => 1000
        ]);

        $response->assertUnprocessable();
    }
}
