<?php

namespace Tests\Feature;

use App\Enums\Permissions;
use App\Enums\Roles;
use App\Models\Connector;
use App\Models\ConnectorType;
use App\Models\Station;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StationLevelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConnectorTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(AdminSeeder::class);
        $this->seed(StationLevelSeeder::class);

        $this->station = Station::factory()->create();
        $this->connectorType = ConnectorType::factory()->create();

        $this->admin = User::factory()->create();
        $this->admin->assignRole(Roles::ADMIN);

        $this->user = User::factory()->create();
        $this->user->assignRole(Roles::USER);
        
    }

    public function test_connector_index_return_connectors(): void
    {
        $response = $this->withHeader('Accept', 'application/json')->get('/connectors/');

        $response->assertOk();
        $response->assertJsonStructure(['connectors']);
    }

    public function test_connector_store_create_connector(): void
    {
        $this->actingAs($this->admin);

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->post('/connectors/store', [
                'station_uuid' => $this->station->uuid,
                'type_id' => $this->connectorType->id,
        ]);
        
        $response->assertCreated();
        $response->assertJsonStructure(['message', 'connector']);
    }

    public function test_connector_store_need_admin_privileges(): void
    {
        $this->actingAs($this->user);

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->post('/connectors/store', [
                'station_uuid' => $this->station->uuid,
                'type_id' => $this->connectorType->id,
        ]);
        
        $response->assertForbidden();
    }

    public function test_connector_store_throw_on_invalid_station_uuid():void
    {
        $this->actingAs($this->admin);

        $response = $this
        ->withHeader('Accept', 'application/json')
        ->post('/connectors/store', [
            'station_uuid' => 'aaa',
            'type_id' => $this->connectorType->id,
        ]);

        $response->assertUnprocessable();
    }

    public function test_connector_store_throw_on_invalid_type_id():void
    {
        $this->actingAs($this->admin);

        $response = $this
        ->withHeader('Accept', 'application/json')
        ->post('/connectors/store', [
            'station_uuid' => $this->station->uuid,
            'type_id' => 'aaa',
        ]);

        $response->assertUnprocessable();
    }

    public function test_connector_show_return_specified_connector(): void
    {
        $connector = Connector::factory()->create();

        $response = $this->withHeader('Accept', 'application/json')->get("/connectors/{$connector->uuid}");

        $response->assertOk();
        $response->assertJsonStructure(['connector']);
    }

    public function test_connector_show_404_on_invalid_connector_uuid(): void
    {
        $response = $this->withHeader('Accept', 'application/json')->get("/connectors/abcbaduii");
        
        $response->assertNotFound();
    }

    public function test_connector_destroy_delete_connector(): void
    {
        $this->actingAs($this->admin);

        $connector = Connector::factory()->create();

        $response = $this->withHeader("Accept", "application/json")->delete("/connectors/delete/{$connector->uuid}");
        
        $response->assertOk();
    }

    public function test_connector_destroy_need_admin_privileges(): void
    {
        $this->actingAs($this->user);
        
        $connector = Connector::factory()->create();

        $response = $this->withHeader("Accept", "application/json")->delete("/connectors/delete/{$connector->uuid}");
        
        $response->assertForbidden();
    }

    public function test_connector_destroy_404_on_invalid_uuid(): void
    {
        $this->actingAs($this->admin);

        $response = $this->withHeader("Accept", "application/json")->delete("/connectors/delete/aaaaa");
        
        $response->assertNotFound();
    }
}
