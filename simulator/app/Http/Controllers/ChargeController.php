<?php

namespace App\Http\Controllers;

use App\Jobs\ChargeVehicle;
use Illuminate\Http\Request;

class ChargeController extends Controller
{
    /**
     * Start a new charging simulation job.
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function startCharging(Request $request)
    {
        try {
            ChargeVehicle::dispatch(
                $request->input('vehicle_uuid', "8955650f-a06d-4813-bc26-af5a89fce99d"),
                $request->input("connector_uuid", "46bcb427-a5b3-4899-a198-5f4a5c4889db"),
                $request->input('starting_charge', 75),
                $request->input('speed', 100)
            );
        } catch (\Exception $e) {
            $code = $e->getCode() === 0 ? 500 : $e->getCode();
            return response($e->getMessage(), $code);
        }

        return response()->json(['message' => 'Charging started successfully.'], 200);
    }
}
