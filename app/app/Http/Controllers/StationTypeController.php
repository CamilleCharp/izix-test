<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\Http\Requests\StationTypeDestroyRequest;
use App\Http\Requests\StationTypeStoreRequest;
use App\Http\Requests\StationTypeUpdateRequest;
use App\Models\StationLevel;
use App\Models\StationType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller in charge of the API operations on the StationTypes model
 */
class StationTypeController extends Controller
{
    /**
     * Display a listing of the station types.
     * 
     * @return JsonResponse The station types infos.
     */
    public function index(): JsonResponse
    {
        $stationTypes = StationType::all()->map->only(['id', 'name', 'level', 'current', 'power'])->toArray();

        return response()->json(['station_types' => $stationTypes]);
    }

    /**
     * Store a newly created station type in storage.
     * 
     * @param StationTypeStoreRequest $request The store request, validated beforehand
     * @see project://app/Http/Requests/StationTypeStoreRequest.php
     * 
     * @return JsonResponse The newly creation station type infos.
     */
    public function store(StationTypeStoreRequest $request): JsonResponse
    {
        $level = $this->getValidLevel($request->input('level'), $request->input('power'));

        $stationType = new StationType();
        $stationType->name = $request->input('name');
        $stationType->current = $request->input('current');
        $stationType->level()->associate($level);
        $stationType->power = $request->input('power');

        $stationType->save();

        return response()->json([
            'message' => "Station type {$stationType->name} created.",
            'station_type' => $stationType
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified station type.
     * 
     * @param StationType $stationType the station type object, found from the route with its id.
     * 
     * @return JsonResponse The station type infos.
     */
    public function show(StationType $stationType): JsonResponse
    {
        return response()->json([
            'station_type' => $stationType->only(['name', 'level', 'current', 'power'])
        ]);
    }

    /**
     * Update the specified station type in storage.
     * 
     * @param StationTypeUpdateRequest $request The update request and its input, validated beforehand.
     * @see project://app/Http/Requests/StationTypeUpdateRequest.php
     * @param StationType $stationType The station type object, found from the route with its id.
     * 
     * @return JsonResponse The updated station type infos.
     */
    public function update(StationTypeUpdateRequest $request, StationType $stationType): JsonResponse
    {
        
        if($request->has('level') || $request->has('power')) {
            $level = $request->input('level', $stationType->level);
            $power = $request->input('power', $stationType->power);

            $newLevel = $this->getValidLevel(
                $request->input('level', $stationType->level),
                $request->input('power', $stationType->power)
            );
            
            if($level !== $stationType->level) {
                $stationType->level()->associate($newLevel);
            }
        }

        $stationType->name = $request->input('name', $stationType->name);
        $stationType->current = $request->input('current', $stationType->current);
        $stationType->power = $request->input('power', $stationType->power);

        $stationType->save();

        return response()->json([
            'message' => "The station type {$stationType->name} has been updated successfully.",
            'station_type' => $stationType
        ]);
    }

    /**
     * Remove the specified station type from storage.
     * 
     * @param StationTypeDestroyRequest $request Mostly used for authorization purposes.
     * @see project://app/Http/Requests/StationTypeDestroyRequest.php
     * @param StationType $stationType the station type object, found from the route with its id.
     */
    public function destroy(StationTypeDestroyRequest $request, StationType $stationType): JsonResponse
    {
        $name = $stationType->name;
        $stationType->delete();

        return response()->json(['message' => "Station type '{$name}' deleted successfully."], Response::HTTP_OK);
    }

    /**
     * Helper function ensuring the station type is in its desired level specs before giving the level infos.
     * 
     * @param int $levelId The id of the level to retrieve
     * @param int $power The power the station type is expected to have, in watts.
     * @throws NotFoundHttpException|Exception 
     * @return StationLevel The level infos.
     */
    private function getValidLevel(int $levelId, int $power): JsonResponse|StationLevel
    {
        $level = StationLevel::where('level', $levelId)->first();

        if(!$level) {
            throw new NotFoundHttpException('Invalid station level ID');
        }

        if($power < $level->minimum_output || $power > $level->maximum_output) {
            throw new Exception("Power output must be between {$level->minimum_output}W and {$level->maximum_output}W for level {$level->level}.");
        }

        return $level;
    }
}
