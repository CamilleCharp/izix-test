<?php

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getServiceWithMockClient(string $className, array $responses)
    {
        $mockResponses = new MockHandler($responses);

        $handlerStack = HandlerStack::create($mockResponses);

        $client = new Client(['handler'=> $handlerStack]);

        return new $className($client);
    }
}
