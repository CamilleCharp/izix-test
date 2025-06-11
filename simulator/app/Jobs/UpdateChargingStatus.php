<?php

namespace App\Jobs;

use App\Services\ChargeService;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * Update a running simulation
 * 
 * @package App\Jobs
 * @subpackage App\Jobs\ChargingSession 
 */
class UpdateChargingStatus implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     * 
     * @param string $sessionUuid The charging session to update.
     * @param string $token The update token, a new one is needed on each update.
     * @param int $batteryCapacity The total battery capacity, avoiding "overcharge".
     * @param int $currentCharge The current charge of the vehicle.
     * @param int $inputPerCycle How much power should be added on each update
     * @param int $secondsBetweenCycle How often should the battery be updated.
     * 
     */
    public function __construct(
        private string $sessionUuid,
        public string $token,
        private int $batteryCapacity,
        private int $currentCharge,
        private int $inputPerCycle,
        private int $secondsBetweenCycle,
    ) {}

    /**
     * The actual simulation happens here.
     * The battery is "charged" before updating the charging session.
     * The job then dispatch itself with the new values.
     * This is to avoid timeout problems as well as a finer tracking of the progress.
     * 
     * If the charge is complete the session automatically end.
     * 
     * @return void
     */
    public function handle(ChargeService $chargeService): void
    {
        $this->currentCharge += $this->inputPerCycle;

        if($this->currentCharge > $this->batteryCapacity) {
            $this->currentCharge = $this->batteryCapacity;
            
            $this->token = $chargeService->updateChargeSession(
                $this->sessionUuid,
                $this->token,
                $this->currentCharge,
                $this->batteryCapacity
            );
            
            $chargeService->endChargeSession($this->sessionUuid, $this->token);

            \Log::info("Done charging !");
            
            return;
        }

        $currentPercentage = ($this->currentCharge / $this->batteryCapacity) * 100;
        
        $this->token = $chargeService->updateChargeSession(
            $this->sessionUuid,
            $this->token,
            $this->currentCharge,
            $this->batteryCapacity
        );

        \Log::info("Charged at {$currentPercentage}%.");
        
        self::dispatch($this->sessionUuid, $this->token, $this->batteryCapacity, $this->currentCharge, $this->inputPerCycle, $this->secondsBetweenCycle)
            ->delay($this->secondsBetweenCycle);

        return;
    }
}
