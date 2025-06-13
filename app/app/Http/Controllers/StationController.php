<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\Http\Requests\StationDestroyRequest;
use App\Http\Requests\StationStoreRequest;
use App\Http\Requests\StationUpdateRequest;
use App\Models\Location;
use App\Models\Station;
use App\Models\StationType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Controller in charge of the operations on the Station model.
 */
class StationController extends Controller
{
    /**
     * Display a listing of the stations.
     * 
     * @return JsonResponse the stations infos.
     */
    public function index(): JsonResponse
    {
        $stations = Station::all()->map->only(['uuid', 'name', 'spot', 'type', 'location', 'last_used_at']);
        return response()->json([
            'stations' => $stations,
        ]);
    }

    /**
     * Store a newly created station in storage.
     * 
     * @param StationStoreRequest $request The store request and its input, validated beforehand
     * @see project://app/Http/Requests/StationStoreRequest.php
     * 
     * @return JsonResponse The newly created station infos.
     */
    public function store(StationStoreRequest $request): JsonResponse
    {
        $type = StationType::findOrFail($request->input('type_id'));
        $location = Location::findOrFail($request->input('location_uuid'));

        $station = new Station();
        $station->name = $request->input('name');
        $station->spot = $request->input('spot');

        $station->type()->associate($type);
        $station->location()->associate($location);

        $station->save();

        return response()->json([
            'message' => "Station {$station->name} created successfully",
            'station' => $station,
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified station.
     * 
     * @param Station $station The station object, found from the route with its uuid
     * 
     * @return JsonResponse The station infos
     */
    public function show(Station $station): JsonResponse
    {
        return response()->json([
            'station' => $station->only(['name', 'spot', 'type', 'location', 'last_used_at']),
        ]);
    }

    /**
     * Update the specified station in storage.
     * 
     * @param StationUpdateRequest $request The update request and its input, validated beforehand.
     * @see project://app/Http/Requests/StationUpdateRequest.php
     * 
     * @return JsonResponse The new station infos.
     */
    public function update(StationUpdateRequest $request, Station $station): JsonResponse
    {
        if($request->has('type_id')) {
            $type = StationType::findOrFail($request->input('type_id'));
            $station->type()->associate($type);
        }

        if($request->has('location_uuid')) {
            $location = Location::findOrFail($request->input('location_uuid'));
            $station->location()->associate($location);
        }

        $station->name = $request->input('name', $station->name);
        $station->spot = $request->input('spot', $station->spot);
        $station->save();

        return response()->json([
            'message' => "Station {$station->name} updated successfully",
            'station' => $station,
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified station from storage.
     * 
     * @param StationDestroyRequest $request Used mostly for authorization purpose.
     * @see project://app/Http/Requests/StationDestroyRequest.php
     * @param Station $station The station object, found from the route with its uuid.
     * 
     * @return JsonResponse
     */
    public function destroy(StationDestroyRequest $request, Station $station): JsonResponse
    {
            $name = $station->name;
            $station->delete();

            return response()->json([
                'message' => "Station {$name} deleted successfully",
            ], Response::HTTP_OK);
    }
}
