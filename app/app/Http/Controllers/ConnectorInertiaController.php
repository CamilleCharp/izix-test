<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConnectorDestroyRequest;
use App\Http\Requests\ConnectorStoreRequest;
use App\Models\Connector;
use App\Models\ConnectorType;
use App\Models\Station;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConnectorInertiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $connectors = Connector::paginate(5)->through(fn ($connector) => [
            'uuid' => $connector->uuid,
            'station' => $connector->station,
            'location' => $connector->station->location,
            'connector_type' => $connector->type,
        ]);

        return Inertia::render("Connectors", [
            "connectors" => $connectors,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("ConnectorCreation", [
            'connector_types' => ConnectorType::all()->map->only('name', 'id'),
            'stations' => Station::all()->map->only(["name","uuid", "location"]),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConnectorStoreRequest $request)
    {
        $station = Station::find($request->input("station_uuid"));
        $type = ConnectorType::find($request->input("type_id"));

        $connector = new Connector();

        $connector->station()->associate($station);
        $connector->type()->associate($type);

        $connector->save();

        return redirect()->route("connectors_show", $connector->uuid)->with("success",true);
    }

    /**
     * Display the specified resource.
     */
    public function show(Connector $connector)
    {
        return Inertia::render("ConnectorShow", [
            'connector' => $connector->load('station', 'type'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Connector $connector)
    {
        return Inertia::render("ConnectorEdit", [
            "connector" => $connector->load("type", "station"),
            "connector_types" => ConnectorType::all()->map->only(['name', 'id']),
            "stations" => Station::all()->map->only(["name","uuid", "location"]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Connector $connector)
    {
        $request->validate([
            'type_id' => 'required|exists:connector_types,id',
            'station_uuid' => 'required|exists:stations,uuid'
        ]);

        $requestConnectorType = ConnectorType::find($request->input('type_id'));
        $requestStation = Station::find($request->input('station_uuid'));

        if(!$connector->type()->is($requestConnectorType)) {
            $connector->type()->associate($requestConnectorType);
        }

        if(!$connector->station()->is($requestStation)) {
            $connector->station()->associate($requestStation);
        }

        $connector->save();

        return redirect()->route('connectors_show', $connector->uuid)->with('success',true);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConnectorDestroyRequest $request, Connector $connector)
    {
        $connector->delete();

        return redirect()->route(route: 'connectors_index')->with(['success',true]);
    }
}
