<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\Http\Requests\ConnectorStoreRequest;
use App\Models\Connector;
use App\Models\ConnectorType;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConnectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(["connectors" => Connector::all()->map->only(["uuid", "station", "type"])]);
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

        return response()->json([
            "message" => "Connector created successfully.",
            "connector" => $connector->only(["uuid", "station", "type"]),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Connector $connector)
    {
        return response()->json([
            "connector" => $connector->only(["uuid", "station", "type"]),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Connector $connector)
    {
        if(auth()->user()->hasPermissionTo(Permissions::DELETE_CONNECTOR)) {
            $connector->delete();
            return response()->json(["message" => "Connector deleted successfully."]);
        }

        return response()->json(["message" => "Unauthorized."], Response::HTTP_UNAUTHORIZED);
    }
}
