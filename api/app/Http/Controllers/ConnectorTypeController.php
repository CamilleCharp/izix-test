<?php

namespace App\Http\Controllers;

use App\Models\ConnectorType;
use Illuminate\Http\Request;

// * Connector Types Logic, no store/update/delete methods as these are statics and managed by direct commands
class ConnectorTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $connectors = ConnectorType::all();

        return response()->json($connectors->map(function ($connector) {
            return [
                'name' => $connector->name,
                'max_voltage' => $connector->max_voltage,
                'max_current' => $connector->max_current,
                'max_watts' => $connector->max_watts,
                'current_type' => $connector->current_type,
            ];
        }));
    }

    /**
     * Display the specified resource.
     */
    public function show(ConnectorType $connectorType)
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
