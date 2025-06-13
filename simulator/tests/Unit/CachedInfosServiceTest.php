<?php

namespace Tests\Unit;

use App\Services\CachedInfosService;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CachedInfosServiceTest extends TestCase
{
    private CachedInfosService $cachedInfosService;

    public function setUp(): void
    {
        parent::setUp();

        $this->cachedInfosService = new CachedInfosService();
    }

    public function test_return_connector_infos_from_cache(): void
    {
        Cache::shouldReceive("remember")
            ->once()
            ->with("aaa-bbb-ccc_infos", 60 * 60, \Closure::class)
            ->andReturn([
                'max_power' => 10000,
                'current_type' => 'AC'
            ]);
        
        ['max_power' => $maxPower, 'current_type' => $currentType] = $this->cachedInfosService->getCachedConnectorInfos(
            'aaa-bbb-ccc'
        );

        $this->assertEquals(10000, $maxPower);
        $this->assertEquals('AC', $currentType);
    }

    public function test_return_connector_infos_from_api(): void
    {
        $cachedInfosService = $this->getServiceWithMockClient(CachedInfosService::class, [
            new Response(200, [], json_encode([
                    'connector' => [
                        'type' => [
                            'current_type' => 'AC',
                            'max_voltage' => 100,
                            'max_current' => 100
                        ]
                    ]
            ]))
        ]);

        ['max_power' => $maxPower, 'current_type' => $currentType] = $cachedInfosService->getCachedConnectorInfos('aaa-bbb-ccc');

        $this->assertEquals(10000, $maxPower);
        $this->assertEquals('AC', $currentType);
    }

    public function test_connectors_throw_on_404(): void
    {
        $cachedInfosServices = $this->getServiceWithMockClient(CachedInfosService::class, [
            new Response(404)
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid connector name');

        $cachedInfosServices->getCachedConnectorInfos('aaa-bbb-ccc');
    }

    public function test_connectors_throw_on_403(): void
    {
        $cachedInfosServices = $this->getServiceWithMockClient(CachedInfosService::class, [
            new Response(403)
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unauthorized to fetch connector infos');

        $cachedInfosServices->getCachedConnectorInfos('aaa-bbb-ccc');
    }

    public function test_connectors_throw_on_500(): void
    {
        $cachedInfosServices = $this->getServiceWithMockClient(CachedInfosService::class, [
            new Response(500)
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('An error occured on the server side, try again in a few time');

        $cachedInfosServices->getCachedConnectorInfos('aaa-bbb-ccc');
    }

    public function test_connectors_throw_on_generic_server_error(): void
    {
        $cachedInfosServices = $this->getServiceWithMockClient(CachedInfosService::class, [
            new Response(501)
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('An error occured, try again in a few time');

        $cachedInfosServices->getCachedConnectorInfos('aaa-bbb-ccc');
    }

    public function test_return_vehicle_infos_from_cache(): void
    {
        Cache::shouldReceive("remember")
            ->once()
            ->with('aaa-bbb-ccc_infos', 60 * 60, \Closure::class)
            ->andReturn([
                'maximum_ac_input' => 1000,
                'maximum_dc_input' => 10000,
                'battery_capacity' => 100000
            ]);

        [
            'maximum_ac_input' => $maxAcInput,
            'maximum_dc_input' => $maxDcInput,
            'battery_capacity' => $batteryCapacity
        ] = $this->cachedInfosService->getCachedVehicleInfos('aaa-bbb-ccc');

        $this->assertEquals(1000, $maxAcInput);
        $this->assertEquals(10000, $maxDcInput);
        $this->assertEquals(100000, $batteryCapacity);
    }

    public function test_return_vehicle_infos_from_api(): void
    {
        $cachedInfosService = $this->getServiceWithMockClient(CachedInfosService::class, [
            new Response(200, [], json_encode(['type' => ['id' => '1']])),
            new Response(200, [], json_encode(['vehicle_type' => ['maximum_ac_input' => 1000, 'maximum_dc_input' => 10000, 'battery_capacity' => 100000]]))
        ]);

        [
            'max_ac_input' => $maxAcInput,
            'max_dc_input' => $maxDcInput,
            'battery_capacity' => $batteryCapacity,
        ] = $cachedInfosService->getCachedVehicleInfos('aaa-bbb-ccc');

        $this->assertEquals(1000, $maxAcInput);
        $this->assertEquals(10000, $maxDcInput);
        $this->assertEquals(100000, $batteryCapacity);
    }

    public function test_vehicles_throw_on_500(): void
    {
        $cachedInfosService = $this->getServiceWithMockClient(CachedInfosService::class, [
            new Response(500)
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('An error occured on the server side, try again in a few time');

        $cachedInfosService->getCachedVehicleInfos('aaa-bbb-ccc');
    }

    public function test_vehicles_throw_on_404(): void
    {
        $cachedInfosService = $this->getServiceWithMockClient(CachedInfosService::class, [
            new Response(404)
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid vehicle name');

        $cachedInfosService->getCachedVehicleInfos('aaa-bbb-ccc');
    }

    public function test_vehicles_throw_on_403(): void
    {
        $cachedInfosService = $this->getServiceWithMockClient(CachedInfosService::class, [
            new Response(403)
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unauthorized to fetch vehicle infos');

        $cachedInfosService->getCachedVehicleInfos('aaa-bbb-ccc');
    }

    public function test_vehicles_throw_on_generic_server_error(): void
    {
        $cachedInfosService = $this->getServiceWithMockClient(CachedInfosService::class, [
            new Response(501)
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('An error occured, try again in a few time');

        $cachedInfosService->getCachedVehicleInfos('aaa-bbb-ccc');
    }


    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }
}
