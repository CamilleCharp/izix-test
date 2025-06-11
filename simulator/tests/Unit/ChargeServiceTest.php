<?php

namespace Tests\Unit;

use App\Services\ChargeService;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ChargeServiceTest extends TestCase
{
    private ChargeService $chargeService;

    public function setUp(): void
    {
        parent::setUp();

        $this->chargeService = new ChargeService();

        Log::shouldReceive('error');
    }

    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_calculate_correct_charge(): void
    {
        ['watts' => $wattValue, 'percent' => $percentValue ] = $this->chargeService->calculateCharge(200, 25);
        ['percent' => $randomValue] = $this->chargeService->calculateCharge(200,-1);
        
        $this->assertEquals(25, $percentValue);
        $this->assertEquals(50, $wattValue);

        $this->assertNotEquals(-1, $randomValue);
    }

    public function test_calculate_correct_charge_time(): void
    {
        ['real' => $realSpeed, 'simulated' => $simulatedSpeed] = $this->chargeService->calculateTimeToComplete(
           1000, 
           100, 
           10, 
           1
        );

        $realSpeed = $realSpeed['hours'] + $realSpeed['minutes'];
        $simulatedSpeed = $simulatedSpeed['hours'] + $simulatedSpeed['minutes'];


        $this->assertEqualsWithDelta(10.0, $realSpeed, 0.1);
        $this->assertEqualsWithDelta(1.0, $simulatedSpeed, 0.1);
    }

    public function test_correctly_start_a_session(): void
    {
        $mockResponses = new MockHandler([
            new Response(200, [], json_encode(['session_uuid' => 'aaa-bbb-ccc', 'token' => 'aaaa'])),
            new Response(500)
        ]);

        $handlerStack = HandlerStack::create($mockResponses);

        $client = new Client(['handler'=> $handlerStack]);

        $mockChargeService = new ChargeService($client);

        ['session_uuid' => $sessionUuid, 'token' => $token] = $mockChargeService->startChargeSession(
            '50',
             'aaa',
              'bbb'
        );

        $this->assertEquals('aaa-bbb-ccc', $sessionUuid);
        $this->assertEquals('aaaa', $token);
    }

    public function test_start_throw_on_403(): void
    {
        $mockChargeService = $this->getServiceWithMockClient(ChargeService::class, [
            new Response(403, [], "Server Error")
        ], );
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Unauthorized to start a session");

        $mockChargeService->startChargeSession(
            '50',
             'aaa',
              'bbb'
        );
    }

    public function test_start_throw_on_500(): void
    {
        $mockChargeService = $this->getServiceWithMockClient(ChargeService::class, [
            new Response(500, [], "Server Error")
        ], );
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("An error occured on the server side, try again in a few time");

        $mockChargeService->startChargeSession(
            '50',
             'aaa',
              'bbb'
        );
    }

    public function test_start_throw_on_generic_server_error(): void
    {
        $mockChargeService = $this->getServiceWithMockClient(ChargeService::class, [
            new Response(501, [], "Server Error")
        ], );
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("An error occured, try again in a few time");

        $mockChargeService->startChargeSession(
            '50',
             'aaa',
              'bbb'
        );
    }

    public function test_update_session_correctly(): void
    {
        $mockChargeService = $this->getServiceWithMockClient(ChargeService::class,[
            new Response(200, [], json_encode(['token' => 'bbbb']))
        ]);

        $token = $mockChargeService->updateChargeSession('aaa-bbb-ccc', 'aaaa', 50, 100);

        $this->assertEquals('bbbb', $token);
    }

    public function test_update_throw_on_404(): void
    {
        $mockChargeService = $this->getServiceWithMockClient(ChargeService::class,[
            new Response(404, [], "Server error")
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid session UUID, could not find the session name");

        $mockChargeService->updateChargeSession('aaa-bbb-ccc', 'aaaa', 50, 100);
    }

    public function test_update_throw_on_403(): void
    {
        $mockChargeService = $this->getServiceWithMockClient(ChargeService::class,[
            new Response(403, [], "Server error")
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Unauthorized to update the session");

        $mockChargeService->updateChargeSession('aaa-bbb-ccc', 'aaaa', 50, 100);
    }

    public function test_update_throw_on_500(): void
    {
        $mockChargeService = $this->getServiceWithMockClient(ChargeService::class,[
            new Response(500, [], "Server error")
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("An error occured on the server side, try again in a few time");

        $mockChargeService->updateChargeSession('aaa-bbb-ccc', 'aaaa', 50, 100);
    }

    public function test_update_throw_on_generic_server_error(): void
    {
        $mockChargeService = $this->getServiceWithMockClient(ChargeService::class,[
            new Response(501, [], "Server error")
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("An error occured, try again in a few time");

        $mockChargeService->updateChargeSession('aaa-bbb-ccc', 'aaaa', 50, 100);
    }

    public function test_end_session_correctly(): void
    {
        $mockChargeService = $this->getServiceWithMockClient(ChargeService::class, [
            new Response(200)
        ]);

        $ended = $mockChargeService->endChargeSession('aaa-bbb-ccc', 'aaaa');

        $this->assertEquals(true, $ended);
    }

    public function test_end_throw_on_404(): void
    {
        $mockChargeService = $this->getServiceWithMockClient(ChargeService::class, [
            new Response(404, [], 'Server error')
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid session UUID, could not find the session name');

        $mockChargeService->endChargeSession('aaa-bbb-ccc', 'aaaa');
    }

    public function test_end_throw_on_403(): void
    {
        $mockChargeService = $this->getServiceWithMockClient(ChargeService::class, [
            new Response(403, [], 'Server error')
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unauthorized to end the session');

        $mockChargeService->endChargeSession('aaa-bbb-ccc', 'aaaa');
    }

    public function test_end_throw_on_500(): void
    {
        $mockChargeService = $this->getServiceWithMockClient(ChargeService::class, [
            new Response(500, [], 'Server error')
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('An error occured on the server side, try again in a few time');

        $mockChargeService->endChargeSession('aaa-bbb-ccc', 'aaaa');
    }

    public function test_end_throw_on_generic_server_error(): void
    {
        $mockChargeService = $this->getServiceWithMockClient(ChargeService::class, [
            new Response(501, [], 'Server error')
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('An error occured, try again in a few time');

        $mockChargeService->endChargeSession('aaa-bbb-ccc', 'aaaa');
    }
}
