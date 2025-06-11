<?php

namespace Tests\Feature;

use App\Enums\Roles;
use App\Models\StationLevel;
use App\Models\StationType;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StationLevelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StationTypeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(StationLevelSeeder::class);
        $this->seed(RoleSeeder::class);

        StationType::factory()->count(3)->create();
        $this->level = StationLevel::inRandomOrder()->first();

        $this->user = User::factory()->create()->assignRole(Roles::USER);
        $this->admin = User::factory()->create()->assignRole(Roles::ADMIN);
    }

    public function test_station_type_index_returns_station_types(): void
    {
        $response = $this->getJson('/stations/types/');

        $response->assertOk();
        $response->assertJsonStructure([
                'station_types' => [
                    '*' => ['id', 'name', 'level', 'current', 'power'],
                ]
        ]);
    }

    public function test_station_type_store_create_station_type(): void
    {
        $this->actingAs($this->admin);

        $name = 'FastCharge X';

        $response = $this->postJson('/stations/types/register', [
            'name' => $name,
            'level' => $this->level->id,
            'current' => 'AC',
            'power' => fake()->numberBetween($this->level->minimum_output, $this->level->maximum_output),
        ]);
        
        $response->assertOk();

        $this->assertDatabaseHas('station_types', ['name' => $name]);
    }

    public function test_station_type_store_need_admin_privilege(): void
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/stations/types/register', [
            'name' => 'Failure IX',
            'level' => $this->level->id,
            'current' => 'AC',
            'power' => fake()->numberBetween($this->level->minimum_output, $this->level->maximum_output),
        ]);

        $response->assertForbidden();
    }

    public function test_station_type_store_fails_with_invalid_power(): void
    {
        $this->actingAs(($this->admin));

        $response = $this->postJson('/stations/types/register', [
            'name' => 'BadPower',
            'level' => $this->level->id,
            'current' => 'DC',
            'power' => 1, // too low
        ]);

        $response->assertInternalServerError();
    }

    public function test_station_type_store_fails_with_invalid_level(): void
    {
        $this->actingAs(($this->admin));        

        $response = $this->postJson('/stations/types/register', [
            'name' => 'BadLevel',
            'level' => 9999,
            'current' => 'DC',
            'power' => 10000,
        ]);

        $response->assertUnprocessable();
    }

    public function test_station_type_show_return_station_type(): void
    {
        $stationType = StationType::factory()->create();

        $response = $this->getJson("/stations/types/{$stationType->id}");

        $response->assertOk()
            ->assertJsonStructure([
                'station_type' => ['name', 'level', 'current', 'power']
            ]);
    }

    public function test_station_type_show_404_on_invalid_id(): void
    {
        $response = $this->getJson("/stations/types/9999999999");

        $response->assertNotFound();
    }

    public function test_station_type_update_modify_station_type(): void
    {
        $this->actingAs(($this->admin));

        $stationType = StationType::factory()->create([
            'name' => "Breakin'",
            'station_level_id' => $this->level->id,
            'power' => 10000,
        ]);

        $response = $this->putJson("/stations/types/update/{$stationType->id}", [
            'name' => "Breakin' 2: Electric Boogaloo",
            'power' => fake()->numberBetween($this->level->minimum_output, $this->level->maximum_output),
            'level' => $this->level->id,
        ]);

        $response->assertOk()
            ->assertJsonFragment(['name' => "Breakin' 2: Electric Boogaloo"]);

        $this->assertDatabaseHas('station_types', ['name' => "Breakin' 2: Electric Boogaloo"]);
    }

    public function test_station_type_update_need_admin_privileges(): void
    {
        $this->actingAs(($this->user));

        $stationType = StationType::factory()->create([
            'name' => "Breakin'",
            'station_level_id' => $this->level->id,
            'power' => 10000,
        ]);

        $response = $this->putJson("/stations/types/update/{$stationType->id}", [
            'name' => "Breakin' 2: Electric Boogaloo",
            'power' => fake()->numberBetween($this->level->minimum_output, $this->level->maximum_output),
            'level' => $this->level->id,
        ]);

        $response->assertForbidden();
    }

    public function test_station_type_update_404_on_invalid_id(): void
    {
        $this->actingAs(($this->admin));

        $response = $this->putJson("/stations/types/update/999999999999", [
            'name' => "Breakin' 2: Electric Boogaloo",
            'power' => fake()->numberBetween($this->level->minimum_output, $this->level->maximum_output),
            'level' => $this->level->id,
        ]);

        $response->assertNotFound();
    }

    public function test_station_type_destroy_deletes_station_type(): void
    {
        $this->actingAs(($this->admin)); 

        $stationType = StationType::factory()->create();

        $response = $this->deleteJson("/stations/types/delete/{$stationType->id}");

        $response->assertOk()
            ->assertJsonFragment(['message' => "Station type '{$stationType->name}' deleted successfully."]);

        $this->assertDatabaseMissing('station_types', ['id' => $stationType->id]);
    }

    public function test_station_type_destroy_need_admin_privileges(): void
    {
        $this->actingAs(($this->user)); 

        $stationType = StationType::factory()->create();

        $response = $this->deleteJson("/stations/types/delete/{$stationType->id}");

        $response->assertForbidden();
    }

    public function test_station_type_destroy_404_on_invalid_id(): void
    {
        $this->actingAs(($this->admin)); 

        $response = $this->deleteJson("/stations/types/delete/999999999999");

        $response->assertNotFound();
    }
}
