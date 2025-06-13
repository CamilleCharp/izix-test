<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationDestroyRequest;
use App\Http\Requests\LocationStoreRequest;
use App\Http\Requests\LocationUpdateRequest;
use App\Models\Location;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * Controller in charge of the API operations on the Location model.
 */
class LocationController extends Controller
{
    /**
     * Display a listing of the locations
     * 
     * @return JsonResponse The locations infos.
     */
    public function index(): JsonResponse
    {
        $locations = Location::all()->map->only(['uuid', 'name', 'coordinate', 'capacity'])->toArray();

        return response()->json(['locations' => $locations]);
    }

    /**
     * Store a newly created location in storage.
     * 
     * @param LocationStoreRequest $request The store request, validated beforehand
     * @see project://app/Http/Requests/LocationStoreRequest.php
     * 
     * @return JsonResponse The newly created location infos.
     */
    public function store(LocationStoreRequest $request):   JsonResponse
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
     * Display the specified location.
     * 
     * @param Location $location The location object, found from the route with its uuid.
     * 
     * @return JsonResponse 
     */
    public function show(Location $location): JsonResponse
    {
        return response()->json([
            'location' => $location->only(['uuid', 'name', 'coordinate', 'capacity', 'tenant']),
        ]);
    }

    /**
     * Update the specified location in storage.
     * 
     * @param LocationUpdateRequest $request The update request, validated beforehand
     * @see project://app/Http/Requests/LocationUpdateRequest.php
     * 
     * @return JsonResponse The new location informations.
     */
    public function update(LocationUpdateRequest $request, Location $location): JsonResponse
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
     * Remove the specified location from storage.
     * 
     * @param LocationDestroyRequest $request Used mostly for authorization purposes.
     * @see project://app/Http/Requests/LocationDestroyRequest.php
     * @param Location $location The location object, found from the route with its uuid.
     * 
     * @return JsonResponse
     */
    public function destroy(LocationDestroyRequest $request, Location $location): JsonResponse
    {
        $name = $location->name;
        $location->delete();

        return response()->json(['message' => "Location {$name} deleted successfully"], Response::HTTP_OK);
    }
}
