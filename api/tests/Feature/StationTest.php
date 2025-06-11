<?php

namespace Tests\Feature;

use App\Enums\Roles;
use App\Models\Location;
use App\Models\Station;
use App\Models\StationType;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StationLevelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;
    protected Location $location;
    protected StationType $type;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(StationLevelSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(AdminSeeder::class);

        $this->location = Location::factory()->create();
        $this->type = StationType::factory()->create();

        $this->admin = User::factory()->create();
        $this->admin->assignRole(Roles::ADMIN);

        $this->user = User::factory()->create();
        $this->user->assignRole(Roles::USER);
    }

    public function test_station_index_returns_stations(): void
    {
        Station::factory()->count(3)->create();

        $response = $this->withHeader('Accept', 'application/json')->get('/stations/');

        $response->assertOk();
        $response->assertJsonStructure(['stations']);
    }

    public function test_station_store_creates_station(): void
    {
        $this->actingAs($this->admin);

        $response = $this->postJson('/stations/store', [
            'name' => 'Central Hub',
            'spot' => 1,
            'type_id' => $this->type->id,
            'location_uuid' => $this->location->uuid->toString(),
        ]);

        $response->assertCreated();
        $response->assertJsonStructure(['message', 'station']);
    }

    public function test_station_store_requires_admin_privileges(): void
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/stations/store', [
            'name' => 'Central Hub',
            'spot' => 1,
            'type_id' => $this->type->id,
            'location_uuid' => $this->location->uuid->toString(),
        ]);

        $response->assertForbidden();
    }

    public function test_station_store_fails_on_invalid_type_id(): void
    {
        $this->actingAs($this->admin);

        $response = $this->postJson('/stations/store', [
            'name' => 'Test Station',
            'spot' => 2,
            'type_id' => 9999,
            'location_uuid' => $this->location->uuid->toString(),
        ]);

        $response->assertUnprocessable();
    }

    public function test_station_store_fails_on_invalid_location_uuid(): void
    {
        $this->actingAs($this->admin);

        $response = $this->postJson('/stations/store', [
            'name' => 'Test Station',
            'spot' => 2,
            'type_id' => $this->type->id,
            'location_uuid' => 'fake-uuid',
        ]);

        $response->assertUnprocessable();
    }

    public function test_station_show_returns_station(): void
    {
        $station = Station::factory()->create();

        $response = $this->getJson("/stations/{$station->uuid}");

        $response->assertOk();
        $response->assertJsonStructure(['station']);
    }

    public function test_station_show_404_on_invalid_uuid(): void
    {
        $response = $this->getJson('/stations/invalid-uuid');

        $response->assertNotFound();
    }

    public function test_station_update_updates_station(): void
    {
        $this->actingAs($this->admin);
        $station = Station::factory()->create();

        $response = $this->putJson("/stations/update/{$station->uuid}", [
            'name' => 'Updated Station',
            'spot' => 3,
            'type_id' => $this->type->id,
            'location_uuid' => $this->location->uuid->toString(),
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['message', 'station']);
    }

    public function test_station_update_requires_admin(): void
    {
        $this->actingAs($this->user);
        $station = Station::factory()->create();

        $response = $this->putJson("/stations/update/{$station->uuid}", [
            'name' => 'Should Not Update',
        ]);

        $response->assertForbidden();
    }

    public function test_station_destroy_deletes_station(): void
    {
        $this->actingAs($this->admin);
        $station = Station::factory()->create();

        $response = $this->deleteJson("/stations/delete/{$station->uuid}");

        $response->assertOk();
    }

    public function test_station_destroy_requires_admin(): void
    {
        $this->actingAs($this->user);
        $station = Station::factory()->create();

        $response = $this->deleteJson("/stations/delete/{$station->uuid}");

        $response->assertForbidden();
    }

    public function test_station_destroy_404_on_invalid_uuid(): void
    {
        $this->actingAs($this->admin);

        $response = $this->deleteJson("/stations/delete/fake-uuid");

        $response->assertNotFound();
    }
}
