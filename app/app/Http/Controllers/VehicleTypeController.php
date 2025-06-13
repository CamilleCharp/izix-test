<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\Http\Requests\VehicleDestroyRequest;
use App\Http\Requests\VehicleTypeDestroyRequest;
use App\Http\Requests\VehicleTypeStoreRequest;
use App\Http\Requests\VehicleTypeUpdateRequest;
use App\Models\VehicleType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VehicleTypeController extends Controller
{
    /**
     * Display a listing of the vehicle type.
     * 
     * @return JsonResponse All the vehicle types infos
     */
    public function index(): JsonResponse
    {
        $vehicleTypes = VehicleType::all()->map->only(['id', 'name', 'maximum_ac_input', 'maximum_dc_input', 'battery_capacity']);

        return response()->json([
            'vehicle_types' => $vehicleTypes
        ]);
    }

    /**
     * Store a newly created vehicle type in storage.
     * 
     * @param VehicleTypeStoreRequest $request The store request and its inputs, validated beforehand.
     * @see project://app/Http/Requests/VehicleTypeStoreRequest.php
     * 
     * @return JsonResponse The newly created vehicle type infos.
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
     * Display the specified vehicle type.
     * 
     * @param VehicleType $vehicleType The vehicle type object, found from the route with its id.
     * 
     * @return JsonResponse The vehicle type infos
     */
    public function show(VehicleType $vehicleType): JsonResponse
    {
        return response()->json([
            'vehicle_type' => $vehicleType->only(['id', 'name', 'maximum_ac_input', 'maximum_dc_input', 'battery_capacity'])
        ]);
    }

    /**
     * Update the specified vehicle type in storage.
     * 
     * @param VehicleTypeUpdateRequest $request The update request and its input, validated beforehand.
     * @see project://app/Http/Requests/VehicleTypeUpdateRequest.php
     * @param VehicleType $vehicleType The vehicle type object, found from the route with its id.
     * 
     * @return JsonResponse The updated vehicle type infos.
     */
    public function update(VehicleTypeUpdateRequest $request, VehicleType $vehicleType): JsonResponse
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
     * Remove the specified vehicle type from storage.
     * 
     * @param VehicleTypeDestroyRequest $request The destroy request, mostly used for authorizaiton purpose.
     * @see project://app/Http/Requests/VehicleTypeDestroyRequest.php
     * 
     * @return JsonResponse
     */
    public function destroy(VehicleTypeDestroyRequest $request, VehicleType $vehicleType): JsonResponse
    {
        $name = $vehicleType->name;
        $vehicleType->delete();

        return response()->json(['message' => "Vehicle type {$name} deleted successfully"], Response::HTTP_OK);
    }
}
