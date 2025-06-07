<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\Http\Requests\StationTypeStoreRequest;
use App\Http\Requests\StationTypeUpdateRequest;
use App\Models\StationLevel;
use App\Models\StationType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stationTypes = StationType::all()->map->only(['name', 'level', 'current', 'power'])->toArray();

        return response()->json(['station_types' => $stationTypes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StationTypeStoreRequest $request)
    {
        $this->getValidLevel($request->input('level'), $request->input('power'));

        $stationType = new StationType();
        $stationType->name = $request->input('name');
        $stationType->current = $request->input('current');
        $stationType->level()->associate($level);
        $stationType->power = $request->input('power');

        $stationType->save();

        return response(status: Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(StationType $stationType)
    {
        return response()->json([
            'station_type' => $stationType->only(['name', 'level', 'current', 'power'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StationTypeUpdateRequest $request, StationType $stationType)
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StationType $stationType)
    {
        if(!auth()->user()->hasPermissionTo(Permissions::DELETE_CHARGING_STATION)) {
            return response()->json(['message' => 'You do not have permission to delete a charging station type.'], 403);
        }
        $name = $stationType->name;
        $stationType->delete();

        return response()->json(['message' => "Station type '{$name}' deleted successfully."], Response::HTTP_OK);
    }

    private function getValidLevel(int $level, int $power): JsonResponse|StationLevel
    {
        $level = StationLevel::where('level', $level)->first();

        if(!$level) {
            return response()->json(['message' => 'Invalid station level'], Response::HTTP_BAD_REQUEST);
        }

        if($power < $level->minimum_output || $power > $level->maximum_output) {
            return response()->json([
                'message' => "Power output must be between {$level->minimum_output}W and {$level->maximum_output}W for level {$level->level}."
            ], Response::HTTP_BAD_REQUEST);
        }

        return $level;
    }
}
