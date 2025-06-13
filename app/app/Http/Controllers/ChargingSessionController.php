<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChargingSessionEndRequest;
use App\Http\Requests\ChargingSessionMonitorRequest;
use App\Http\Requests\ChargingSessionStartRequest;
use App\Http\Requests\ChargingSessionUpdateRequest;
use App\Models\ChargingSession;
use App\Models\Connector;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

/**
 * Controller in charge of the chargin session operations
 * The methods naming diverge from the convention for better clarity
 */
class ChargingSessionController extends Controller
{
    /**
     * Start a new charging session for a vehicle on the specified connector
     * 
     * 
     * @param ChargingSessionStartRequest $request The request and its input, validated beforehand.
     * @see project://app/Http/Request/ChargingSessionStartRequest.php
     * @return JsonResponse The new session UUID and its token
     */
    public function start(ChargingSessionStartRequest $request): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($request->input('vehicle_uuid'));
        $connector = Connector::findOrFail($request->input('connector_uuid'));

        $session = new ChargingSession();

        $session->starting_battery_percent = $request->input('starting_battery_percent');
        $session->current_battery_percent = $request->input('starting_battery_percent');
        $session->vehicle()->associate($vehicle);
        $session->connector()->associate($connector);
        $session->token = Str::random(64);

        $session->save();

        return response()->json([
            "success" => true,
            "session_uuid" => $session->uuid,
            "token" => $session->token,
        ], Response::HTTP_CREATED);
    }

    /**
     * Update the specified running charging session
     * 
     * @param ChargingSessionUpdateRequest $request The update request and its input, validated beforehand.
     * @see project://app/Http/Request/ChargingSessionUpdateRequest.php
     * @param ChargingSession $session The running session, fetched from the uuid
     * @return JsonResponse the new update authorization token for this session
     */
    public function update(ChargingSessionUpdateRequest $request, ChargingSession $session): JsonResponse
    {
        $session->current_battery_percent = $request->input('current_battery_percent');
        $session->token = Str::random(64);

        $session->save();

        return response()->json([
            "success" => true,
            "token" => $session->token,
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified running charging session
     * 
     * @param ChargingSessionEndRequest $request The ending request and its input, validated beforehand.
     * @see project://app/Http/Request/ChargingSessionEndRequest.php
     * @param ChargingSession $session The running session, fetched from the route with uuid
     * @return JsonResponse
     */
    public function end(ChargingSessionEndRequest $request, ChargingSession $session): JsonResponse
    {
        $session->ended_at = now();
        $session->token = null;

        $session->save();

        return response()->json([
            "success" => true,
        ], Response::HTTP_OK);
    }


    /**
     * Check the status of a running charging session
     * @param ChargingSessionMonitorRequest $request Mostly here for pre-authorization purposes
     * @see project://app/Http/Request/ChargingSessionMonitorRequest.php
     * @param ChargingSession $session The session, fetched from the route with uuid
     * @return JsonResponse The session informations
     */
    public function monitor(ChargingSessionMonitorRequest $request, ChargingSession $session): JsonResponse
    {
        return response()->json($session->only([
            'starting_battery_percent',
            'current_battery_percent',
            'connector',
            'started_at',
            'last_status_update'
        ]));
    }
}
