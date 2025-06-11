<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\Http\Requests\VehicleDestroyRequest;
use App\Http\Requests\VehicleStoreRequest;
use App\Http\Requests\VehicleUpdateRequest;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VehicleController extends Controller
{
    /**
     * Display a listing of the vehicles.
     * 
     * @return JsonResponse All the vehicles infos
     */
    public function index(): JsonResponse
    {
        $vehicles = Vehicle::all()->map(function ($vehicle) {
            return [
                'uuid' => $vehicle->uuid,
                'owner' => $vehicle->owner->only(['id', 'name', 'email']),
                'type' => $vehicle->type->only(['id', 'name']),
                'plate' => $vehicle->license_plate,
            ];
        });

        return response()->json($vehicles);
    }

    /**
     * Store a newly created vehicle in storage.
     * 
     * @param VehicleStoreRequest $request The store request and its input, validated beforehand
     * @see project://app/Http/Requests/VehicleStoreRequest.php
     * 
     * @return JsonResponse The newly created vehicle infos (+ owner and type) 
     */
    public function store(VehicleStoreRequest $request): JsonResponse
    {
        $vehicle = new Vehicle();
        
        if($request->has('owner_id')) {
            $owner = User::findOrFail($request->input('owner_id'))->first();
            $vehicle->owner()->associate($owner);
        } else {
            $vehicle->owner()->associate(auth()->user());
        }

        $type = VehicleType::findOrFail($request->input('type'));
        $vehicle->type()->associate($type);

        $vehicle->license_plate = $request->input('plate');

        $vehicle->save();

        return response()->json([
            'message' => "Vehicle with license plate {$vehicle->license_plate} registered successfully",
            'vehicle' => [
                'uuid' => $vehicle->uuid,
                'owner' => $vehicle->owner->only(['id', 'name', 'email']),
                'type' => $vehicle->type->only(['id', 'name']),
                'plate' => $vehicle->license_plate,
            ]
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified vehicle.
     * 
     * @param Vehicle $vehicle The vehicle object, found from the route with its uuid.
     * 
     * @return JsonResponse The vehicle infos (+ owner and type).
     */
    public function show(Vehicle $vehicle): JsonResponse
    {
        return response()->json([
            'uuid' => $vehicle->uuid,
            'owner' => $vehicle->owner->only(['id', 'name', 'email']),
            'type' => $vehicle->type->only(['id', 'name']),
            'plate' => $vehicle->license_plate,
        ]);
    }

    /**
     * Update the specified vehicle in storage.
     * 
     * @param VehicleUpdateRequest $request the update request and its input, validated beforehand.
     * @see project://app/Http/Requests/VehicleUpdateRequest.php
     * 
     * @return JsonResponse the updated vehicle infos (+ owner and type)
     */
    public function update(VehicleUpdateRequest $request, Vehicle $vehicle): JsonResponse
    {
        // Set another user if the request come from someone authorized to do so.
        if($request->has('owner')) {
            $owner = User::findOrFail($request->input('owner_id'));
            $vehicle->owner()->associate($owner);
        }

        if($request->has('type')) {
            $type = VehicleType::findOrFail($request->input('type'));
            $vehicle->type()->associate($type);
        }

        $vehicle->license_plate = $request->input('plate', $vehicle->license_plate);

        $vehicle->save();

        return response()->json([
            'message' => "Vehicle with license plate {$vehicle->license_plate} updated successfully",
            'vehicle' => [
                'uuid' => $vehicle->uuid,
                'owner' => $vehicle->owner->only(['id', 'name', 'email']),
                'type' => $vehicle->type->only(['id', 'name']),
                'plate' => $vehicle->license_plate,
            ]
            ], Response::HTTP_OK);
    }

    /**
     * Remove the specified vehicle from storage.
     * 
     * @param VehicleDestroyRequest $request The destroy request, used mostly for authorization purposes.
     * @see project://app/Http/Requests/VehicleDestroyRequest.php
     * @param Vehicle $vehicle The vehicle object, found from the route with its uuid.
     * 
     * @return JsonResponse
     */
    public function destroy(VehicleDestroyRequest $request, Vehicle $vehicle): JsonResponse
    {
            $plate = $vehicle->license_plate;
            $vehicle->delete();

            return response()->json(['message' => "Your vehicle with license plate {$plate} deleted successfully"], Response::HTTP_OK);
    }
}
