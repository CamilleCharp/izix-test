<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class APIEntryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(AdminSeeder::class);
    }

    /**
     * A basic feature test example.
     */
    public function test_api_denied_while_unlogged(): void
    {
        $response = $this
            ->getJson('/api/');

        $response->assertStatus(401);
    }

    public function test_api_allowed_while_logged(): void
    {
        $this->actingAs(User::find(1)->first());

        $response = $this
            ->withHeader('API-KEY', env('FAKE_API_KEY'))
            ->getJson('/api/');

        $response->assertStatus(200);
    }
}
