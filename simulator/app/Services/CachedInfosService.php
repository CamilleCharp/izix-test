<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Cache;

class CachedInfosService
{
    private Client $client;

    public function __construct(?Client $client = null) 
    {
        $this->client = $client ?: new Client(['base_uri' => env('API_URL')]);
    }
    /**
     * Retrieve the connector infos from cache.
     * If it isn't available, fetch it from the API.
     * 
     * @return array The connector infos
     */
    public function getCachedConnectorInfos(string $connectorUuid):array
    {
        $cacheKey  = $connectorUuid . '_infos';

        return Cache::remember($cacheKey, 60 * 60, function () use ($connectorUuid) {
            try {
                $connectorRes = $this->client->get('/api/connectors/' . $connectorUuid, [
                    "headers" => [
                        "Accept" => 'application/json',
                        "API-KEY" => env("FAKE_API_KEY")
                    ],
                ]);
            } catch (BadResponseException $e) {
                match($e->getCode()) {
                    500 => throw new Exception('An error occured on the server side, try again in a few time', 500),
                    404 => throw new Exception('Invalid connector name', 404),
                    403 => throw new Exception('Unauthorized to fetch connector infos', 403),
                    default => throw new Exception('An error occured, try again in a few time', 500)
                };
            }
            ['connector' => $connector] = json_decode($connectorRes->getBody()->getContents(), true);
            ['current_type' => $currentType, 'max_voltage' => $maxVoltage, 'max_current' => $maxCurrent] = $connector['type'];

            return [
                'max_power' => $maxVoltage * $maxCurrent,
                'current_type' => $currentType
            ];
        });
    }

    /**
     * Retrieve the vehicle infos from cache.
     * If it isn't available, fetch it from the API.
     * 
     * @return array The vehicle infos
     */
    public function getCachedVehicleInfos(string $vehicleUuid): array
    {
        $cacheKey = $vehicleUuid . '_infos';

        return Cache::remember($cacheKey, 60 * 60, function() use ($vehicleUuid) {
            try {
                $vehicleRes = $this->client->get('/api/vehicles/' . $vehicleUuid, [
                    "headers" => [
                        "Accept" => 'application/json',
                        "API-KEY" => env("FAKE_API_KEY")
                    ],
                ]);
            } catch (BadResponseException $e) {
                match($e->getCode()) {
                    500 => throw new Exception('An error occured on the server side, try again in a few time', 500),
                    404 => throw new Exception('Invalid vehicle name', 404),
                    403 => throw new Exception('Unauthorized to fetch vehicle infos', 403),
                    default => throw new Exception('An error occured, try again in a few time', 500)
                };
            }
            $vehicleTypeId = json_decode($vehicleRes->getBody()->getContents(), true)['type']['id'];

            
            $vehicleType = $this->client->get('/api/vehicles/types/' . $vehicleTypeId, [
                "headers" => [
                    "Accept" => 'application/json',
                    "API-KEY" => env("FAKE_API_KEY")
                ],
            ]);

            [
                'maximum_ac_input' => $maxAcInput, 
                'maximum_dc_input' => $maxDcInput, 
                'battery_capacity' => $batteryCapacity
            ] = json_decode($vehicleType->getBody()->getContents(), true)['vehicle_type'];
        
            return [
                'max_ac_input' => $maxAcInput,
                'max_dc_input' => $maxDcInput,
                'battery_capacity' => $batteryCapacity,
            ];
        });
    }
}