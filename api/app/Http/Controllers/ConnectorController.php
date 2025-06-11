<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\Http\Requests\ConnectorDestroyRequest;
use App\Http\Requests\ConnectorStoreRequest;
use App\Models\Connector;
use App\Models\ConnectorType;
use App\Models\Station;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Controller in charge of the API operations for the Connector model
 */
class ConnectorController extends Controller
{
    /**
     * Display a listing of the connectors.
     */
    public function index(): JsonResponse
    {
        return response()->json(["connectors" => Connector::all()->map->only(["uuid", "station", "type"])]);
    }

    /**
     * Store a newly created connector in storage.
     * @param ConnectorStoreRequest The request and its input, validated beforehand
     * @see project://app/Http/Requests/ConnectorStoreRequest.php
     * 
     * @return JsonResponse The new connector infos
     */
    public function store(ConnectorStoreRequest $request): JsonResponse
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
     * Display the specified connector.
     * @param Connector $connector The connector object, found from the route with its uuid.
     * 
     * @return JsonResponse the connector infos.
     */
    public function show(Connector $connector): JsonResponse
    {
        return response()->json([
            "connector" => $connector->only(["uuid", "station", "type"]),
        ]);
    }

    /**
     * Remove the specified connector from storage.
     * 
     * @param ConnectorDestroyRequest $request Here mostly for authorization
     * @see project://app/Http/Requests/ConnectorDestroyRequest.php
     * @param Connector $connector The connector object, found from the route with its uuid,
     * 
     * @return JsonResponse
     */
    public function destroy(ConnectorDestroyRequest $request, Connector $connector): JsonResponse
    {
        $connector->delete();
        return response()->json(["message" => "Connector deleted successfully."]);
    }
}
