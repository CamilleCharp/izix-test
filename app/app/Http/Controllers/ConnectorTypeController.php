<?php

namespace App\Http\Controllers;

use App\Models\ConnectorType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controller in charge of the API operations on the ConnectorType model.
 * No store/update/delete methods as these are statics and managed by direct commands
 */
class ConnectorTypeController extends Controller
{
    /**
     * Display a listing of the connector types.
     * 
     * @return JsonResponse All connector types infos
     */
    public function index(): JsonResponse
    {
        $connectors = ConnectorType::all();

        $connectorTypes = $connectors->map(function ($connector) {
            return [
                "id"=> $connector->id,
                'name' => $connector->name,
                'max_voltage' => $connector->max_voltage,
                'max_current' => $connector->max_current,
                'max_watts' => $connector->max_watts,
                'current_type' => $connector->current_type,
            ];
        });

        return response()->json(['connector_types' => $connectorTypes]);
    }

    /**
     * Display the specified connector type.
     * 
     * @param ConnectorType $connectorType The connector type object, found from the route with its id.
     */
    public function show(ConnectorType $connectorType): JsonResponse
    {
        return response()->json([
            'name' => $connectorType->name,
            'max_voltage' => $connectorType->max_voltage,
            'max_current' => $connectorType->max_current,
            'max_watts' => $connectorType->max_watts,
            'current_type' => $connectorType->current_type,
        ]);        
    }
}
