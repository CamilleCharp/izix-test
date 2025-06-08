<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\Http\Requests\VehicleStoreRequest;
use App\Http\Requests\VehicleUpdateRequest;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
     * Store a newly created resource in storage.
     */
    public function store(VehicleStoreRequest $request)
    {
        $vehicle = new Vehicle();

        if(auth()->user()->hasPermissionTo(Permissions::REGISTER_EXTERNAL_VEHICLE) && $request->has('owner')) {
            $owner = User::findOrFail($request->input('owner'));

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
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        return response()->json([
            'uuid' => $vehicle->uuid,
            'owner' => $vehicle->owner->only(['id', 'name', 'email']),
            'type' => $vehicle->type->only(['id', 'name']),
            'plate' => $vehicle->license_plate,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehicleUpdateRequest $request, Vehicle $vehicle)
    {
        if(auth()->user()->hasPermissionTo(Permissions::REGISTER_EXTERNAL_VEHICLE) && $request->has('owner')) {
            $owner = User::findOrFail($request->input('owner'));

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
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        if(!auth()->user()->hasPermissionTo(Permissions::DELETE_VEHICLE) && !auth()->user()->hasPermissionTo(Permissions::DELETE_EXTERNAL_VEHICLE)) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        if($vehicle->owner->is(auth()->user())) {
            $plate = $vehicle->license_plate;
            $vehicle->delete();

            return response()->json(['message' => "Your vehicle with license plate {$plate} deleted successfully"], Response::HTTP_OK);
        }

        if(auth()->user()->hasPermissionTo(Permissions::DELETE_EXTERNAL_VEHICLE)) {
            $plate = $vehicle->license_plate;
            $vehicle->delete();

            return response()->json(['message' => "Vehicle with license plate {$plate} deleted successfully"], Response::HTTP_OK);
        }

        return response()->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
    }
}
