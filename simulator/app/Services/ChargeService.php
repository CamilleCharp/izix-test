<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ChargeService {
    private Client $client;

    public function __construct(?Client $client = null) 
    {
        $this->client = $client ?: new Client(['base_uri' => env('API_URL')]);
    }

    // ========================================================================
    // API REQUESTS
    // ========================================================================

    public function startChargeSession(int $startingBatteryPercent, string $vehicleUuid, string $connectorUuid)
    {
        try {
            $chargingSessionRes = $this->client->post(
                "/charging-sessions/start", 
                [
                    "headers" => [
                        "Accept" => 'application/json',
                        "API-KEY" => env("SIMULATOR_API_KEY")
                    ],
                    "multipart" => [
                        [
                            "name" => "starting_battery_percent",
                            "contents" => $startingBatteryPercent
                        ],
                        [
                            "name" => "vehicle_uuid",
                            "contents" => $vehicleUuid
                        ],
                        [
                            "name" => "connector_uuid",
                            "contents" => $connectorUuid
                        ]
                    ]
                ]
            );
        } catch (BadResponseException $e) {
            Log::error(message: "Error while starting the session for vehicle {$vehicleUuid} on {$connectorUuid} : {$e->getMessage()}");

            match($e->getCode()) {
                500 => throw new Exception('An error occured on the server side, try again in a few time'),
                403 => throw new Exception('Unauthorized to start a session'),
                default => throw new Exception('An error occured, try again in a few time')
            };
        }
        ['session_uuid' => $sessionUuid, 'token' => $token] = json_decode($chargingSessionRes->getBody()->getContents(), true);

        return ['session_uuid' => $sessionUuid, 'token' => $token];
    }

    public function updateChargeSession(string $sessionUuid, string $token, int $currentCharge, int $batteryCapacity)
    {
        try {

            $updateRes = $this->client->put("/charging-sessions/update/{$sessionUuid}", 
            [
                "headers" => [
                    "Accept" => 'application/json',
                    "API-KEY" => env("SIMULATOR_API_KEY")
                ],
                "form_params" => [
                    "current_battery_percent" => round(($currentCharge / $batteryCapacity) * 100),
                    "token" => $token
                ]
            ]);
        } catch (BadResponseException $e) {
            Log::error("Error while updating the session {$sessionUuid}  : {$e->getMessage()}");

            match($e->getCode()) {
                500 => throw new Exception('An error occured on the server side, try again in a few time', 500),
                404 => throw new Exception('Invalid session UUID, could not find the session name', 404),
                403 => throw new Exception('Unauthorized to update the session', 403),
                default => throw new Exception('An error occured, try again in a few time', 500)
            };
        }

        ['token' => $token] = json_decode($updateRes->getBody()->getContents(), true);

        return $token;
    }

    public function endChargeSession(string $sessionUuid, string $token): bool
    {
        try {
            $this->client->post("/charging-sessions/end/{$sessionUuid}", 
            [
                "headers" => [
                    "Accept" => 'application/json',
                    "API-KEY" => env("SIMULATOR_API_KEY")
                ],
                "multipart" => [
                    [
                        "name" => "token",
                        "contents" => $token
                    ]
                ]
            ]);
        } catch (BadResponseException $e) {
            Log::error("Error while ending the session {$sessionUuid}  : {$e->getMessage()}");

            match($e->getCode()) {
                500 => throw new Exception('An error occured on the server side, try again in a few time', 500),
                404 => throw new Exception('Invalid session UUID, could not find the session name', 404),
                403 => throw new Exception('Unauthorized to end the session', 403),
                default => throw new Exception('An error occured, try again in a few time', 500)
            };
        } 

        return true;
    }

    // ========================================================================
    // HELPERS
    // ========================================================================

    public function calculateCharge(int $batteryCapacity, int $chargePercentage = -1) 
    {
        $chargePercentage = $chargePercentage === -1 ? (random_int(0, 80)) : $chargePercentage;

        return [
            'watts' => ($chargePercentage / 100) * $batteryCapacity,
            'percent' => $chargePercentage
        ];
    }

    /**
     * Calculate the time it would take to complete a charge.
     * Calculate at both real speed and simulated speed.
     * 
     * @param int $batteryCharge The current battery charge.
     * @param int $powerInput How much power is sent to the vehicle.
     * @param int $efficiency Optional, how much power is lost to real world conditons.
     * @return array The total time needed, in real time and simulation.
     */
    public function calculateTimeToComplete(int|float $batteryCharge, int $powerInput, int $speed, int|float $efficiency = 0.9)
    {
        $realHoursToComplete = ($batteryCharge) / ($powerInput * $efficiency);
        $realHours = floor($realHoursToComplete);
        $realMinutes = round(($realHoursToComplete - $realHours) * 60); 

        $simulatedHoursToComplete = ($batteryCharge / ($powerInput * $efficiency * $speed));
        $simulatedHours = floor($simulatedHoursToComplete);
        $simulatedMinutes = round(($simulatedHoursToComplete - $simulatedHours) * 60);

        return [
            'real' => ['hours' => $realHours, 'minutes' => $realMinutes],
            'simulated' => ['hours' => $simulatedHours, 'minutes' => $simulatedMinutes]
        ];
    }




}