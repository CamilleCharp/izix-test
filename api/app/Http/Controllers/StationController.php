<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\Http\Requests\StationStoreRequest;
use App\Http\Requests\StationUpdateRequest;
use App\Models\Location;
use App\Models\Station;
use App\Models\StationType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stations = Station::all()->map->only(['uuid', 'name', 'spot', 'type', 'location', 'last_used_at']);
        return response()->json([
            'stations' => $stations,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StationStoreRequest $request)
    {
        $type = StationType::findOrFail($request->input('type_id'));
        $location = Location::findOrFail($request->input('location_uuid'));

        $station = new Station();
        $station->name = $request->input('name');
        $station->spot = $request->input('spot');

        $station->type()->associate($type);
        $station->location()->associate($location);

        $station->save();

        return response()->json([
            'message' => "Station {$station->name} created successfully",
            'station' => $station,
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Station $station)
    {
        return response()->json([
            'station' => $station->only(['name', 'spot', 'type', 'location', 'last_used_at']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StationUpdateRequest $request, Station $station)
    {
        if($request->has('type_id')) {
            $type = StationType::findOrFail($request->input('type_id'));
            $station->type()->associate($type);
        }

        if($request->has('location_uuid')) {
            $location = Location::findOrFail($request->input('location_uuid'));
            $station->location()->associate($location);
        }

        $station->name = $request->input('name', $station->name);
        $station->spot = $request->input('spot', $station->spot);
        $station->save();

        return response()->json([
            'message' => "Station {$station->name} updated successfully",
            'station' => $station,
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Station $station)
    {
        if(auth()->user()->hasPermissionTo(Permissions::DELETE_CHARGING_STATION)) {
            $name = $station->name;
            $station->delete();

            return response()->json([
                'message' => "Station {$name} deleted successfully",
            ], Response::HTTP_OK);
        }
    }
}
