<?php

namespace App\Jobs;

use App\Enums\Current;
use App\Services\CachedInfosService;
use App\Services\ChargeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;

/**
 * Start the charging simulation
 * 
 * This job is in charge of starting a charging session. Operating as a charging station.
 * A charging session is supposed to start when a vehicle plug into a connector
 * Some external logic is not applied here, namely :
 * - The communication between the vehicle and the charging station (ISO 15118)
 * - The retrieval of the vehicle ID from the given informations
 * 
 * @package App\Jobs
 * @subpackage App\Jobs\ChargingSession 
 */
class ChargeVehicle implements ShouldQueue
{
    use Queueable;

    /**
     * How much watts actually goes into the vehicle
     * Could be lower than the connector max power
     * @var int
     */
    private int $realInput;
    /**
     * The battery capacity, in Wh
     * @var int
     */
    private int $batteryCapacity;

    /**
     * Prepare the charging simulation
     * 
     * Get the vehicle and connector informations and set the correct power input.
     * 
     * @param string $vehicleUuid On which vehicle to run the simulation
     * @param string $connectorUuid On which connector to run the simulation
     * @param int $startingCharge The starting battery charge, a value inferior to 0 will set it at random. Default: -1
     * @param int $speed The speed multiplicator, for instance with a value of 10, the vehicle will charge in 6minutes the equivalent of an hour. Default: 100
     * @param int $efficiency How efficient the charging will be, Default: 0.9 (90% efficiency)
     * 
     * @return void
     */
    public function __construct(
        private string $vehicleUuid,
        private string $connectorUuid,
        private int $startingCharge = -1,
        private int $speed = 100,
        private int|float $efficiency = 0.9
    )
    {
        $cachedInfos = new CachedInfosService();
        
        ['max_power' => $maxPower, 'current_type' => $currentType] = $cachedInfos->getCachedConnectorInfos($this->connectorUuid);
        ['battery_capacity' => $batteryCapacity, 'max_ac_input' => $maxAcInput, 'max_dc_input' => $maxDcInput] = $cachedInfos->getCachedVehicleInfos($this->vehicleUuid);
        
        $this->batteryCapacity = $batteryCapacity;
        $this->realInput = match($currentType) {
            "AC" => $maxAcInput < $maxPower ? $maxAcInput : $maxPower,
            "DC" => $maxDcInput < $maxPower ? $maxDcInput : $maxPower,
        };

    }

    /**
     * Start the simulation
     * 
     * Launch the simulation, by creating a new charging session via the API.
     * The update on the charging session are handled by another job : UpdateChargingStatus
     * 
     * @param ChargeService $chargeService Dependency injection of an helper class for charge operations.
     */
    public function handle(ChargeService $chargeService): void
    {
        ['watts' => $startingCharge, 'percent' => $startingChargePercent] = $chargeService->calculateCharge($this->batteryCapacity, $this->startingCharge);

        ['real' => $realTime, 'simulated' => $simulatedTime] = $chargeService->calculateTimeToComplete(
            $this->batteryCapacity - $this->startingCharge,
            $this->realInput,
            $this->speed,
        );

        \Log::info("Charging the vehicle from {$startingChargePercent}%, at real speed it would take {$realTime['hours']}h{$realTime['minutes']}min, at x{$this->speed} it will take {$simulatedTime['hours']}h{$simulatedTime['minutes']}min");


        ['session_uuid' => $sessionUuid, 'token' => $token] = $chargeService->startChargeSession(
            $startingChargePercent,
            $this->vehicleUuid,
            $this->connectorUuid
        );
        
        
        $inputPerSecond = ($this->realInput * 0.9) / 3600;
        $secondsBetweenCycles = 5;

        $inputPerCycle = $inputPerSecond * $secondsBetweenCycles * $this->speed;

        UpdateChargingStatus::dispatch($sessionUuid, $token, $this->batteryCapacity, $startingCharge, $inputPerCycle, $secondsBetweenCycles);
    }
}
