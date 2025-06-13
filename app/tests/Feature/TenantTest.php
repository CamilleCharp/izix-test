<?php

namespace Tests\Feature;

use App\Enums\Roles;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TenantTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeader("API-KEY", env('FAKE_API_KEY'));

        $this->seed(RoleSeeder::class);

        $this->user = User::factory()->create()->assignRole(Roles::USER);
        $this->admin = User::factory()->create()->assignRole(Roles::ADMIN);

        Tenant::factory()->count(3)->create();
    }

    public function test_tenant_index_returns_tenants(): void
    {
        $this->actingAs($this->user);

        $response = $this->getJson('/api/tenants');

        $response->assertOk()
            ->assertJsonStructure([
                'tenants' => [
                    '*' => ['uuid', 'name'],
                ],
            ]);
    }

    public function test_tenant_store_creates_new_tenant(): void
    {
        $this->actingAs($this->admin);

        $response = $this->postJson('api/tenants/store', [
            'name' => 'NewTenantName',
        ]);

        $response->assertOk()
            ->assertJsonFragment(['name' => 'NewTenantName']);

        $this->assertDatabaseHas('tenants', ['name' => 'NewTenantName']);
    }

    public function test_tenant_store_requires_admin_privileges(): void
    {
        $this->actingAs($this->user);

        $response = $this->postJson('api/tenants/store', [
            'name' => 'UnauthorizedTenant',
        ]);

        $response->assertForbidden();
    }

    public function test_tenant_show_returns_tenant(): void
    {
        $this->actingAs($this->user);

        $tenant = Tenant::factory()->create();

        $response = $this->getJson("/api/tenants/{$tenant->uuid}");

        $response->assertOk()
            ->assertJsonStructure(['tenant' => ['uuid', 'name']]);
    }

    public function test_tenant_show_404_on_invalid_uuid(): void
    {
        $this->actingAs($this->user);

        $response = $this->getJson('/api/tenants/aaaa');

        $response->assertNotFound();
    }

    public function test_tenant_update_modifies_tenant(): void
    {
        $this->actingAs($this->admin);

        $tenant = Tenant::factory()->create(['name' => 'OldTenantName']);

        $response = $this->putJson("/api/tenants/update/{$tenant->uuid}", [
            'name' => 'UpdatedTenantName',
        ]);

        $response->assertOk()
            ->assertJsonFragment(['name' => 'UpdatedTenantName']);

        $this->assertDatabaseHas('tenants', ['name' => 'UpdatedTenantName']);
    }

    public function test_tenant_update_requires_admin_privileges(): void
    {
        $this->actingAs($this->user);

        $tenant = Tenant::factory()->create(['name' => 'OldTenantName']);

        $response = $this->putJson("/api/tenants/update/{$tenant->uuid}", [
            'name' => 'UpdatedTenantName',
        ]);

        $response->assertStatus(status: Response::HTTP_FORBIDDEN);
    }

    public function test_tenant_update_404_on_invalid_uuid(): void
    {
        $this->actingAs($this->admin);

        $response = $this->putJson('/api/tenants/update/aaaa', [
            'name' => 'Nope',
        ]);

        $response->assertNotFound();
    }

    public function test_tenant_destroy_deletes_record(): void
    {
        $this->actingAs($this->admin);

        $tenant = Tenant::factory()->create();

        $response = $this->deleteJson("/api/tenants/delete/{$tenant->uuid}");

        $response->assertOk()
            ->assertJsonFragment(['message' => "Tenant '{$tenant->name}' deleted successfully."]);

        $this->assertDatabaseMissing('tenants', ['uuid' => $tenant->uuid]);
    }

    public function test_tenant_destroy_requires_admin_privileges(): void
    {
        $this->actingAs($this->user);

        $tenant = Tenant::factory()->create();

        $response = $this->deleteJson("/api/tenants/delete/{$tenant->uuid}");

        $response->assertForbidden();
    }

    public function test_tenant_destroy_404_on_invalid_uuid(): void
    {
        $this->actingAs($this->admin);

        $response = $this->deleteJson('/tenants/delete/aaaa');

        $response->assertNotFound();
    }
}
