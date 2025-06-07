<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationStoreRequest;
use App\Http\Requests\LocationUpdateRequest;
use App\Models\Location;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::all()->map->only(['uuid', 'name', 'coordinate', 'capacity'])->toArray();

        return response()->json(['locations' => $locations]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LocationStoreRequest $request)
    {
        $tenant = Tenant::findOrFail(id: $request->input('tenant'));
        $location = new Location();

        $location->name = $request->input('name');
        $location->coordinate = $request->input('coordinate', [0, 0]);
        $location->capacity = $request->input('capacity');
        $location->tenant()->associate($tenant);
        
        $location->save();

        return response()->json(['message' => "Location {$location->name} created successfully", 'location' => $location], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        return response()->json([
            'location' => $location->only(['uuid', 'name', 'coordinate', 'capacity', 'tenant']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LocationUpdateRequest $request, Location $location)
    {
        $location->name = $request->input('name', $location->name);
        $location->coordinate = $request->input('coordinate', $location->coordinate);
        $location->capacity = $request->input('capacity', $location->capacity);

        if ($tenantUuid = $request->input('tenant')) {
            $tenant = Tenant::findOrFail($tenantUuid);
            $location->tenant()->associate($tenant);
        }

        $location->save();

        return response()->json(['message' => "Location {$location->name} updated successfully", 'location' => $location]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $name = $location->name;

        $location->delete();

        return response()->json(['message' => "Location {$name} deleted successfully"], Response::HTTP_OK);
    }
}
