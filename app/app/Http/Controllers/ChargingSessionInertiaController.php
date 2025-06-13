<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\Models\ChargingSession;
use App\Models\Connector;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChargingSessionInertiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $canViewAllChargingSessions = auth()->user()->hasPermissionTo(Permissions::VIEW_EXTERNAL_CHARGING_SESSIONS);

        $formatChargingSessions = fn ($chargingSession) => [
            'uuid' => $chargingSession->uuid,
            'started_at' => $chargingSession->started_at,
            'ended_at' => $chargingSession->ended_at ? Carbon::parse($chargingSession->ended_at)->toIso8601String() : null,
            'starting_battery_percent' => $chargingSession->starting_battery_percent,
            'current_battery_percent' => $chargingSession->current_battery_percent,
            'vehicle' => $chargingSession->vehicle->type->name,
            'license_plate' => $chargingSession->vehicle->license_plate,
            'connector' => $chargingSession->connector->uuid,
            'station' => $chargingSession->connector->station->name,
            'location' => $chargingSession->connector->station->location->name, 
        ];
        
        if($canViewAllChargingSessions) {
            $chargingSessions = ChargingSession::paginate(20)->through($formatChargingSessions);
        } else {
            $validVehicleUuids = Vehicle::where('owner_id', auth()->user()->id)->get('id')->pluck('id');

            $chargingSessions = ChargingSession::whereIn('vehicle_uuid', $validVehicleUuids)->paginate(20)->through($formatChargingSessions);
        }

        return Inertia::render("ChargingSessions", [
            "charging_sessions" => $chargingSessions,
        ]);
    }


    /**
     * Display the form to start a new charging session simulation
     */
    public function prepareSimulation(Request $request)
    {
        $vehicles = Vehicle::where('owner_id', auth()->user()->id)
        ->get()
        ->map->only([
            'uuid',
            'name',
            'license_plate'
        ]);

        $connectors = Connector::all()->map->only([
            'uuid',
            'station',
            'type',
        ]);

        return Inertia::render('ChargingSimulationPreparation', [
            'vehicles' => $vehicles,
            'connectors' => $connectors,
        ]);
    }


}
