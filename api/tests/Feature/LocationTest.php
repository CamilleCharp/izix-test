<?php

namespace Tests\Feature;

use App\Enums\Roles;
use App\Models\Location;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed(RoleSeeder::class);
        $this->seed(AdminSeeder::class);

        $this->tenant = Tenant::factory()->create();

        $this->admin = User::factory()->create();
        $this->admin->assignRole(Roles::ADMIN);

        $this->user = User::factory()->create();
        $this->user->assignRole(Roles::USER);
    }

    public function test_location_index_returns_locations(): void
    {
        Location::factory()->count(3)->create();

        $response = $this->withHeader('Accept', 'application/json')->get('/locations/');

        $response->assertOk();
        $response->assertJsonStructure(['locations']);
    }

    public function test_location_store_creates_location(): void
    {
        $this->actingAs($this->admin);

        $response = $this->postJson('/locations/store', [
            'name' => 'Test Location',
            'coordinate' => [fake()->latitude(), fake()->longitude()],
            'capacity' => 10,
            'tenant' => $this->tenant->uuid,
        ]);
        $response->assertCreated();
        $response->assertJsonStructure(['message', 'location']);
    }

    public function test_location_store_requires_admin_privileges(): void
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/locations/store', [
            'name' => 'Test Location',
            'coordinate' => [fake()->latitude(), fake()->longitude()],
            'capacity' => 10,
            'tenant' => $this->tenant->uuid,
        ]);

        $response->assertForbidden();
    }

    public function test_location_show_returns_specific_location(): void
    {
        $location = Location::factory()->create();

        $response = $this->getJson("/locations/{$location->uuid}");

        $response->assertOk();
        $response->assertJsonStructure(['location']);
    }

    public function test_location_show_404_on_invalid_uuid(): void
    {
        $response = $this->getJson('/locations/invalid-uuid');

        $response->assertNotFound();
    }

    public function test_location_update_updates_location(): void
    {
        $this->actingAs($this->admin);

        $location = Location::factory()->create();

        $response = $this->putJson("/locations/update/{$location->uuid}", [
            'name' => 'Updated Location',
            'capacity' => 99,
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['message', 'location']);
        $this->assertEquals('Updated Location', $response['location']['name']);
    }

    public function test_location_destroy_deletes_location(): void
    {
        $this->actingAs($this->admin);

        $location = Location::factory()->create();

        $response = $this->deleteJson("/locations/delete/{$location->uuid}");

        $response->assertOk();
    }

    public function test_location_destroy_requires_admin_privileges(): void
    {
        $this->actingAs($this->user);

        $location = Location::factory()->create();

        $response = $this->deleteJson("/locations/delete/{$location->uuid}");

        $response->assertForbidden();
    }

    public function test_location_destroy_404_on_invalid_uuid(): void
    {
        $this->actingAs($this->admin);

        $response = $this->deleteJson('/locations/delete/invalid-uuid');

        $response->assertNotFound();
    }
}
