<?php

namespace Tests\Feature;

use App\Jobs\ChargeVehicle;
use App\Jobs\UpdateChargingStatus;
use App\Services\ChargeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ChargeVehicleTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_launch_job_from_post_request(): void
    {
        Bus::fake([
            ChargeVehicle::class,
        ]);

        Cache::shouldReceive("remember")
        ->with('aaa-bbb-ccc_infos', 60 * 60, \Closure::class)
        ->andReturn([
            'max_ac_input' => 1000,
            'max_dc_input' => 10000,
            'battery_capacity' => 100000
        ]);

        Cache::shouldReceive("remember")
        ->with("ddd-eee-fff_infos", 60 * 60, \Closure::class)
        ->andReturn([
            'max_power' => 10000,
            'current_type' => 'AC'
        ]);

        $response = $this->post('/charge/start', [
            'speed' => 2000,
            'starting_charge' => 90,
            'vehicle_uuid' => "aaa-bbb-ccc",
            'connector_uuid' => 'ddd-eee-fff'
        ]);
        
        $response->assertStatus(200);
        Bus::assertDispatched(ChargeVehicle::class);
    }

    public function test_can_charge_completely(): void
    {
        $this->mock(ChargeService::class, function(MockInterface $mock) {
            $mock->shouldReceive('updateChargeSession')
                ->times(11)
                ->andReturn('aaaa');

            $mock->shouldReceive('endChargeSession')
                ->once()
                ->andReturn(true);
        });
        
        UpdateChargingStatus::dispatchSync('aaa-bbb-ccc', 'bbbb', 1000, 0, 100, 0);
    }

    public function test_end_charge_properly(): void
    {
        $this->mock(ChargeService::class, function(MockInterface $mock) {
            $mock->shouldReceive('updateChargeSession')
                ->once()
                ->andReturn('aaaa');

            $mock->shouldReceive('endChargeSession')
                ->once()
                ->andReturn(true);
        });

        UpdateChargingStatus::dispatchSync('aaa-bbb-ccc', 'bbbb', 1000, 1000, 100, 0);
    }
}
