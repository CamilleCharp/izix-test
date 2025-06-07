<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\Http\Requests\VehicleTypeStoreRequest;
use App\Http\Requests\VehicleTypeUpdateRequest;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VehicleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return VehicleType::all()->map->only(['id', 'name', 'maximum_ac_input', 'maximum_dc_input', 'battery_capacity']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehicleTypeStoreRequest $request)
    {
        $vehicleType = VehicleType::create([
            'name'=> $request->name,
            'maximum_ac_input' => $request->input('maximum_ac_input'),
            'maximum_dc_input' => $request->input('maximum_dc_input'),
            'battery_capacity' => $request->input('battery_capacity'),
        ]);

        return response()->json([
            'message' => 'Vehicle type created successfully',
            'vehicle_type' => $vehicleType->only(['id', 'name', 'maximum_ac_input', 'maximum_dc_input', 'battery_capacity']),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleType $vehicleType)
    {
        return $vehicleType->only(['id', 'name', 'maximum_ac_input', 'maximum_dc_input', 'battery_capacity']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehicleTypeUpdateRequest $request, VehicleType $vehicleType)
    {
        $vehicleType->update([
            'name' => $request->input('name', $vehicleType->name),
            'maximum_ac_input' => $request->input('maximum_ac_input', $vehicleType->maximum_ac_input),
            'maximum_dc_input' => $request->input('maximum_dc_input', $vehicleType->maximum_dc_input),
            'battery_capacity' => $request->input('battery_capacity', $vehicleType->battery_capacity),
        ]);

        return response()->json([
            'message' => 'Vehicle type updated successfully',
            'vehicle_type' => $vehicleType->only(['id', 'name', 'maximum_ac_input', 'maximum_dc_input', 'battery_capacity']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleType $vehicleType)
    {
        if(auth()->user()->hasPermissionTo(Permissions::DELETE_VEHICLE_TYPE)) {
            $name = $vehicleType->name;
            $vehicleType->delete();
            return response()->json(['message' => "Vehicle type {$name} deleted successfully"], Response::HTTP_OK);
        }

        return response()->json(['message' => 'You do not have permission to delete vehicle types'], Response::HTTP_FORBIDDEN);
    }
}
