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

class ConnectorTypeTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();        
    }

    public function test_connector_index_return_connectors(): void
    {
        $response = $this->withHeader('Accept', 'application/json')->get('/connectors/types');
        
        $response->assertOk();
        $response->assertJsonStructure(['connector_types']);
    }

    public function test_connector_show_return_specified_connector(): void
    {
        $connectorType = ConnectorType::factory()->create();

        $response = $this->withHeader('Accept', 'application/json')->get("/connectors/types/{$connectorType->id}");
        
        $response->assertOk();
        $response->assertJsonStructure(['name','max_voltage','max_current','max_watts','current_type']);
    }

    public function test_connector_show_404_on_invalid_connector_uuid(): void
    {
        $response = $this->withHeader('Accept', 'application/json')->get("/connectors/types/abcbaduii");
        
        $response->assertNotFound();
    }
}
