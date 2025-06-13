<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\Http\Requests\VehicleDestroyRequest;
use App\Http\Requests\VehicleStoreRequest;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VehicleInertiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $canViewAllVehicles = auth()->user()->hasPermissionTo(Permissions::VIEW_EXTERNAL_VEHICLES);

        if($canViewAllVehicles) {
            $vehicles = Vehicle::paginate(5)->through(fn ($vehicle) => [
                'uuid' => $vehicle->uuid,
                'type' => $vehicle->type,
                'owner' => $vehicle->owner,
                'license_plate' => $vehicle->license_plate,
                'battery_capacity' => $vehicle->type->license_plate_id,
            ]);
        } else {
            $vehicles = Vehicle::where('owner_id', auth()->user()->id)->paginate(5)->through(fn ($vehicle) => [
                'uuid' => $vehicle->uuid,
                'type' => $vehicle->type,
                'owner' => $vehicle->owner,
                'license_plate' => $vehicle->license_plate,
                'battery_capacity' => $vehicle->type->license_plate_id,
            ]);
        }
        
        return Inertia::render("Vehicles", [
            "vehicles" => $vehicles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("VehicleCreation", [
            'vehicle_types' => VehicleType::all()->map->only(['id', 'name', 'maximum_ac_input', 'maximum_dc_input'])
        ]);
    }

    /**
     * Store a newly created vehicle in storage.
     * 
     * @param VehicleStoreRequest $request The store request and its input, validated beforehand
     * @see project://app/Http/Requests/VehicleStoreRequest.php
     * 
     * @return RedirectResponse redirect to the vehicle details page
     */
    public function store(VehicleStoreRequest $request): RedirectResponse
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

        return redirect()->route('vehicles_show', $vehicle->uuid)->with('success',true);
    }


    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        return Inertia::render("VehicleShow", [
            'vehicle' => $vehicle->load('owner', 'type'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        return Inertia::render("VehicleEdit", [
            'vehicle' => $vehicle->load('type'),
            'vehicle_types' => VehicleType::all()->map->only(['id', 'name', 'maximum_ac_input', 'maximum_dc_input'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
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

        return redirect()->route('vehicles_show', $vehicle->uuid)->with('success',true);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleDestroyRequest $request, Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('vehicles_index')->with(['success',true]);
    }
}
