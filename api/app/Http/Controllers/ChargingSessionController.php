<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChargingSessionEndRequest;
use App\Http\Requests\ChargingSessionMonitorRequest;
use App\Http\Requests\ChargingSessionStartRequest;
use App\Http\Requests\ChargingSessionStatusChangeRequest;
use App\Http\Requests\ChargingSessionUpdateRequest;
use App\Models\ChargingSession;
use App\Models\Connector;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ChargingSessionController extends Controller
{
    public function start(ChargingSessionStartRequest $request)
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

    public function update(ChargingSessionUpdateRequest $request, ChargingSession $session)
    {
        $session->current_battery_percent = $request->input('current_battery_percent');
        $session->token = Str::random(64);

        $session->save();

        return response()->json([
            "success" => true,
            "token" => $session->token,
        ], Response::HTTP_OK);
    }

    public function end(ChargingSessionEndRequest $request, ChargingSession $session)
    {
        $session->ended_at = now();
        $session->token = null;

        $session->save();

        return response()->json([
            "success" => true,
        ], Response::HTTP_OK);
    }

    public function monitor(ChargingSessionMonitorRequest $request, ChargingSession $session)
    {
        return $session->only(['starting_battery_percent', 'current_battery_percent', 'connector', 'started_at', 'last_status_update']);
    }
}
