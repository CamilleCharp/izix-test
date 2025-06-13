<?php

namespace App\Http\Controllers;

use App\Http\Requests\StationDestroyRequest;
use App\Models\Station;
use App\Models\StationType;
use App\Models\Location;
use App\Http\Requests\StationStoreRequest;
use App\Http\Requests\StationUpdateRequest;
use Inertia\Inertia;

class StationInertiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stations = Station::paginate(5)->through(fn ($station) => [
            'uuid' => $station->uuid,
            'name' => $station->name,
            'spot' => $station->spot,
            'type' => $station->type,
            'location' => $station->location,
        ]);

        return Inertia::render("Stations", [
            "stations" => $stations,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("StationCreation", [
            'station_types' => StationType::all()->map->only(['id', 'name']),
            'locations' => Location::all()->map->only(['uuid', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StationStoreRequest $request)
    {
        $station = new Station();
        $station->name = $request->input('name');
        $station->spot = $request->input('spot');
        $station->type()->associate(StationType::findOrFail($request->input('type_id')));
        $station->location()->associate(Location::findOrFail($request->input('location_uuid')));
        $station->save();

        return redirect()->route('stations_show', $station->uuid)->with('success', true);
    }

    /**
     * Display the specified resource.
     */
    public function show(Station $station)
    {
        return Inertia::render("StationShow", [
            'station' => $station->load('type', 'location'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Station $station)
    {
        return Inertia::render("StationEdit", [
            'station' => $station->load('type', 'location'),
            'station_types' => StationType::all()->map->only(['id', 'name', 'current', 'power']),
            'locations' => Location::all()->map->only(['uuid', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StationUpdateRequest $request, Station $station)
    {
        $station->name = $request->input('name');
        $station->spot = $request->input('spot');
        $station->type()->associate(StationType::findOrFail($request->input('type_id')));
        $station->location()->associate(Location::findOrFail($request->input('location_uuid')));
        $station->save();

        return redirect()->route('stations_show', $station->uuid)->with('success', true);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StationDestroyRequest $request, Station $station)
    {
        $station->delete();

        return redirect()->route('stations_index')->with('success', true);
    }
}